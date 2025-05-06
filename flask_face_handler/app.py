import os
import cv2
import numpy as np
import base64
import json
from datetime import datetime
from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:@localhost:3306/saralkakshyaproject_face_db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)

# Load Haar Cascade for face detection
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# ---------- DATABASE MODELS ----------
class FaceData(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    student_id = db.Column(db.Integer, unique=True, nullable=False)
    institute_id = db.Column(db.Integer, nullable=False)
    histogram = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

# ---------- UTILITY FUNCTIONS ----------
def decode_and_save_image(base64_str, path):
    try:
        # Remove data:image/jpeg;base64, if present
        if ',' in base64_str:
            base64_str = base64_str.split(',')[1]

        img_data = base64.b64decode(base64_str)
        np_arr = np.frombuffer(img_data, np.uint8)
        img = cv2.imdecode(np_arr, cv2.IMREAD_GRAYSCALE)
        return img
    except Exception as e:
        print(f"Error decoding image: {str(e)}")
        return None

def crop_face(image):
    if image is None:
        return None

    # Detect faces in the image
    faces = face_cascade.detectMultiScale(image, scaleFactor=1.1, minNeighbors=5)
    if len(faces) > 0:
        # Take the first detected face
        (x, y, w, h) = faces[0]
        return image[y:y + h, x:x + w]  # Crop the face
    return None  # Return None if no face is detected

def lbp_histogram(image):
    if image is None:
        return None

    h, w = image.shape
    lbp = np.zeros((h - 2, w - 2), dtype=np.uint8)
    for i in range(1, h - 1):
        for j in range(1, w - 1):
            center = image[i, j]
            binary = (
                (image[i - 1, j - 1] > center) << 7 |
                (image[i - 1, j] > center) << 6 |
                (image[i - 1, j + 1] > center) << 5 |
                (image[i, j + 1] > center) << 4 |
                (image[i + 1, j + 1] > center) << 3 |
                (image[i + 1, j] > center) << 2 |
                (image[i + 1, j - 1] > center) << 1 |
                (image[i, j - 1] > center) << 0
            )
            lbp[i - 1, j - 1] = binary
    hist, _ = np.histogram(lbp.ravel(), bins=256, range=(0, 256))
    hist = hist.astype("float")
    hist /= (hist.sum() + 1e-7)
    return hist.tolist()

def euclidean_distance(hist1, hist2):

    #Calculate Euclidean distance between two histograms
    if hist1 is None or hist2 is None:
        return float('inf')

    # Convert to numpy arrays for efficient computation
    h1 = np.array(hist1)
    h2 = np.array(hist2)

    # Calculate Euclidean distance
    distance = np.sqrt(np.sum((h1 - h2) ** 2))
    return distance

# ---------- FLASK ROUTES ----------
@app.route('/register-face', methods=['POST'])
def register_face():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        images = data.get('images')

        if not student_id or not images or len(images) != 5:
            return jsonify({'error': 'Missing or invalid data'}), 400

        all_histograms = []

        for base64_img in images:  # No need for `idx` or folder logic
            img = decode_and_save_image(base64_img, None)  # Pass `None` for path
            if img is None:
                return jsonify({'error': 'Failed to decode image'}), 400

            face_image = crop_face(img)
            if face_image is None:
                return jsonify({'error': 'No face detected in image'}), 400

            face_image  = resize_face(face_image)

            hist = lbp_histogram(face_image)
            if hist is None:
                return jsonify({'error': 'Failed to compute histogram'}), 400

            all_histograms.append(hist)

        avg_histogram = np.mean(all_histograms, axis=0).tolist()

        # Save to database (unchanged)
        face_data = FaceData(student_id=student_id, institute_id=institute_id, histogram=json.dumps(avg_histogram))
        db.session.add(face_data)
        db.session.commit()

        return jsonify({'success': True, 'message': 'Face registered successfully'}), 200
    except Exception as e:
        return jsonify({'error': f'Server error: {str(e)}'}), 500


@app.route('/has-face', methods=['POST'])
def face_exists():
    try:
        data = request.json
        institute_id = data.get('institute_id')
        student_id = data.get('student_id')

        # Validate input parameters
        if not institute_id or not student_id:
            return jsonify({
                'success': False,
                'error': 'Missing required parameters'
            }), 400

        # Check if the student exists in the database
        face_data = FaceData.query.filter_by(
            student_id=student_id,
            institute_id=institute_id
        ).first()

        if face_data:
            return jsonify({
                'success': True,
                'exists': True,
                'message': 'Face data exists for this student',
                'student_id': student_id,
                'institute_id': institute_id
            }), 200
        else:
            return jsonify({
                'success': True,
                'exists': False,
                'message': 'No face data found for this student',
                'student_id': student_id,
                'institute_id': institute_id
            }), 200

    except Exception as e:
        return jsonify({
            'success': False,
            'error': f'Server error: {str(e)}'
        })


@app.route('/update-face', methods=['POST'])
def update_face():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        images = data.get('images')

        if not student_id or not images or len(images) != 5:
            return jsonify({'error': 'Missing or invalid data'}), 400

        # Check if student exists
        existing_face = FaceData.query.filter_by(student_id=student_id).first()
        if not existing_face:
            return jsonify({'error': 'Student not found in database'}), 404

        all_histograms = []

        for base64_img in images:
            img = decode_and_save_image(base64_img, None)
            if img is None:
                return jsonify({'error': 'Failed to decode image'}), 400

            face_image = crop_face(img)
            if face_image is None:
                return jsonify({'error': 'No face detected in image'}), 400

            face_image  = resize_face(face_image)

            hist = lbp_histogram(face_image)
            if hist is None:
                return jsonify({'error': 'Failed to compute histogram'}), 400

            all_histograms.append(hist)

        avg_histogram = np.mean(all_histograms, axis=0).tolist()

        # Update existing record
        existing_face.histogram = json.dumps(avg_histogram)
        existing_face.institute_id = institute_id
        existing_face.created_at = datetime.utcnow()

        db.session.commit()

        return jsonify({
            'success': True,
            'message': 'Face data updated successfully',
            'student_id': student_id,
            'institute_id': institute_id
        }), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'Server error: {str(e)}'}), 500

@app.route('/recognize-face', methods=['POST'])
def recognize_face():
    try:
        data = request.json
        image_base64 = data.get('image')
        institute_id = data.get('institute_id')

        if not image_base64 or not institute_id:
            return jsonify({'error': 'Missing image data or institute_id'}), 400

        # Decode and process the image
        img = decode_and_save_image(image_base64, None)
        if img is None:
            return jsonify({'error': 'Failed to decode image'}), 400

        # Crop the face
        face_image = crop_face(img)
        if face_image is None:
            return jsonify({'error': 'No face detected in image'}), 400

        face_image  = resize_face(face_image)

        # Compute the LBP histogram
        input_hist = lbp_histogram(face_image)
        if input_hist is None:
            return jsonify({'error': 'Failed to compute histogram'}), 400

        # Query the database for stored face data within the same institute
        stored_faces = FaceData.query.filter_by(institute_id=institute_id).all()
        min_distance = float('inf')
        matched_student_id = None

        # Compare with stored histograms using Euclidean distance
        for face in stored_faces:
            stored_hist = json.loads(face.histogram)
            distance = euclidean_distance(input_hist, stored_hist)

            if distance < min_distance:
                min_distance = distance
                matched_student_id = face.student_id

        # Threshold for face recognition (adjust based on testing)
        threshold = 0.75
        if min_distance <= threshold and matched_student_id is not None:
            # Calculate confidence score (inverse relationship with distance)
            confidence = 1 - (min_distance / threshold)
            return jsonify({
                'success': True,
                'student_id': matched_student_id,
                'institute_id': institute_id,
                'confidence': confidence,
                'distance': float(min_distance)  # Convert numpy float to Python float
            }), 200
        else:
            return jsonify({
                'success': False,
                'message': 'No matching face found',
                'distance': float(min_distance)  # Include distance for debugging
            }), 200

    except Exception as e:
        return jsonify({'error': f'Server error: {str(e)}'}), 500

def resize_face(image, size=(100, 100)):
    return cv2.resize(image, size, interpolation=cv2.INTER_AREA)

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True, host='0.0.0.0', port=5000)
