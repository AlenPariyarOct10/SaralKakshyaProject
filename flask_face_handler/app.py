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

def crop_face(image, student_id=None, save_folder="face_images", margin_ratio=0.1):
    """
    Detect, crop, and save face images with proper organization
    Args:
        image: Input grayscale image
        student_id: Optional ID for filename
        save_folder: Folder to save cropped faces
        margin_ratio: Percentage of width/height to add as margin (0-1)
    Returns:
        Cropped face image or None if no face detected
    """
    if image is None:
        return None

    # Create save folder if it doesn't exist
    os.makedirs(save_folder, exist_ok=True)

    # Apply histogram equalization to improve face detection
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
    image = clahe.apply(image)

    # Detect faces with better parameters
    faces = face_cascade.detectMultiScale(
        image,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(50, 50),
        maxSize=(300, 300),
        flags=cv2.CASCADE_SCALE_IMAGE
    )

    if len(faces) == 0:
        return None

    # Get the largest face
    (x, y, w, h) = max(faces, key=lambda f: f[2] * f[3])

    # Add margin instead of reducing (better for feature extraction)
    margin_x = int(w * margin_ratio)
    margin_y = int(h * margin_ratio)

    new_x = max(x - margin_x, 0)
    new_y = max(y - margin_y, 0)
    new_w = min(w + 2 * margin_x, image.shape[1] - new_x)
    new_h = min(h + 2 * margin_y, image.shape[0] - new_y)

    # Ensure minimum size
    if new_w < 50 or new_h < 50:
        return None

    cropped_face = image[new_y:new_y + new_h, new_x:new_x + new_w]

    # Resize to standard size with better interpolation
    cropped_face = cv2.resize(cropped_face, (128, 128), interpolation=cv2.INTER_CUBIC)

    # Apply additional preprocessing
    cropped_face = cv2.GaussianBlur(cropped_face, (3, 3), 0)

    # Generate and save face image
    if student_id:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"face_{student_id}_{timestamp}.jpg"
        save_path = os.path.join(save_folder, filename)
        cv2.imwrite(save_path, cropped_face)

    return cropped_face

def improved_lbp_histogram(image, radius=1, n_points=8, grid_x=4, grid_y=4):
    """
    Compute improved LBP histogram with spatial grid for better discrimination
    """
    if image is None:
        return None

    h, w = image.shape

    # Use appropriate data type for LBP values
    max_lbp_value = 2 ** n_points - 1
    if max_lbp_value <= 255:
        lbp_dtype = np.uint8
    else:
        lbp_dtype = np.uint16

    # Calculate LBP
    lbp = np.zeros((h, w), dtype=lbp_dtype)

    for i in range(radius, h - radius):
        for j in range(radius, w - radius):
            center = image[i, j]
            lbp_value = 0

            # Sample points in a circle
            for p in range(n_points):
                angle = 2 * np.pi * p / n_points
                x = i + radius * np.cos(angle)
                y = j + radius * np.sin(angle)

                # Bilinear interpolation
                x1, y1 = int(x), int(y)
                x2, y2 = min(x1 + 1, h - 1), min(y1 + 1, w - 1)

                # Handle boundary conditions
                if 0 <= x1 < h and 0 <= y1 < w:
                    if x1 == x2 and y1 == y2:
                        pixel_value = image[x1, y1]
                    else:
                        # Bilinear interpolation
                        dx, dy = x - x1, y - y1
                        if x2 < h and y2 < w:
                            pixel_value = (1-dx)*(1-dy)*image[x1,y1] + dx*(1-dy)*image[x2,y1] + \
                                         (1-dx)*dy*image[x1,y2] + dx*dy*image[x2,y2]
                        else:
                            pixel_value = image[x1, y1]
                else:
                    pixel_value = center  # Use center value for out-of-bounds

                # Build LBP value bit by bit
                if pixel_value >= center:
                    lbp_value |= (1 << p)

            lbp[i, j] = lbp_value

    # Create spatial histogram
    cell_h = max(1, h // grid_y)
    cell_w = max(1, w // grid_x)
    histograms = []

    for i in range(grid_y):
        for j in range(grid_x):
            start_h = i * cell_h
            end_h = min((i + 1) * cell_h, h)
            start_w = j * cell_w
            end_w = min((j + 1) * cell_w, w)

            if start_h < h and start_w < w:
                cell_lbp = lbp[start_h:end_h, start_w:end_w]
                hist, _ = np.histogram(cell_lbp.ravel(), bins=max_lbp_value + 1, range=(0, max_lbp_value + 1))

                # Normalize histogram
                hist = hist.astype("float")
                hist_sum = hist.sum()
                if hist_sum > 0:
                    hist /= hist_sum

                histograms.extend(hist.tolist())

    return histograms

def chi_square_distance(hist1, hist2):
    """Improved chi-square distance calculation"""
    if hist1 is None or hist2 is None:
        return float('inf')

    h1 = np.array(hist1)
    h2 = np.array(hist2)

    # Ensure same length
    if len(h1) != len(h2):
        return float('inf')

    # Compute chi-square distance with better epsilon handling
    epsilon = 1e-10
    denominator = h1 + h2 + epsilon
    numerator = (h1 - h2) ** 2

    # Only compute where denominator is significant
    mask = denominator > epsilon
    chi_sq = 0.5 * np.sum(numerator[mask] / denominator[mask])

    return chi_sq

# Euclidean distance
def euclidean_distance(hist1, hist2):
    """Alternative distance metric"""
    if hist1 is None or hist2 is None:
        return float('inf')

    h1 = np.array(hist1)
    h2 = np.array(hist2)

    if len(h1) != len(h2):
        return float('inf')

    return np.sqrt(np.sum((h1 - h2) ** 2))

def combined_distance(hist1, hist2):
    """Combine multiple distance metrics for better accuracy"""
    chi_sq = chi_square_distance(hist1, hist2)
    euclidean = euclidean_distance(hist1, hist2)

    # Normalize and combine
    return 0.7 * chi_sq + 0.3 * euclidean

# ---------- FLASK ROUTES ----------
@app.route('/register-face', methods=['POST'])
def register_face():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        base64_img = data.get('image')

        if not student_id or not base64_img:
            return jsonify({'error': 'Missing or invalid data'}), 400

        img = decode_and_save_image(base64_img, None)
        if img is None:
            return jsonify({'error': 'Failed to decode image'}), 400

        face_image = crop_face(img, student_id)
        if face_image is None:
            return jsonify({'error': 'No face detected in image'}), 400

        hist = improved_lbp_histogram(face_image)
        if hist is None:
            return jsonify({'error': 'Failed to compute histogram'}), 400

        # Check if student already exists
        existing_face = FaceData.query.filter_by(student_id=student_id).first()
        if existing_face:
            return jsonify({'error': 'Student already registered'}), 400

        # Save to database
        face_data = FaceData(
            student_id=student_id,
            institute_id=institute_id,
            histogram=json.dumps(hist)
        )
        db.session.add(face_data)
        db.session.commit()

        return jsonify({'success': True, 'message': 'Face registered successfully'}), 200

    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'Server error: {str(e)}'}), 500

@app.route('/has-face', methods=['POST'])
def face_exists():
    try:
        data = request.json
        institute_id = data.get('institute_id')
        student_id = data.get('student_id')

        if not institute_id or not student_id:
            return jsonify({
                'success': False,
                'error': 'Missing required parameters'
            }), 400

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
        }), 500

@app.route('/update-face', methods=['POST'])
def update_face():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        image = data.get('image')  # Changed from 'images' to 'image'

        if not student_id or not image:
            return jsonify({'error': 'Student ID and image are required'}), 400

        existing_face = FaceData.query.filter_by(student_id=student_id).first()
        if not existing_face:
            return jsonify({'error': 'Student not found in database'}), 404

        img = decode_and_save_image(image, None)
        if img is None:
            return jsonify({'error': 'Invalid image provided'}), 400

        face_image = crop_face(img, student_id)
        if face_image is None:
            return jsonify({'error': 'No face detected in the image'}), 400

        hist = improved_lbp_histogram(face_image)
        if hist is None:
            return jsonify({'error': 'Could not process face features'}), 400

        existing_face.histogram = json.dumps(hist)
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

        img = decode_and_save_image(image_base64, None)
        if img is None:
            return jsonify({'error': 'Failed to decode image'}), 400

        face_image = crop_face(img)
        if face_image is None:
            return jsonify({'error': 'No face detected in image'}), 400

        input_hist = improved_lbp_histogram(face_image)
        if input_hist is None:
            return jsonify({'error': 'Failed to compute histogram'}), 400

        stored_faces = FaceData.query.filter_by(institute_id=institute_id).all()

        if not stored_faces:
            return jsonify({
                'success': False,
                'message': 'No registered faces found for this institute'
            }), 200

        min_distance = float('inf')
        matched_student_id = None
        all_distances = []

        for face in stored_faces:
            stored_hist = json.loads(face.histogram)
            distance = combined_distance(input_hist, stored_hist)
            all_distances.append((distance, face.student_id))

            if distance < min_distance:
                min_distance = distance
                matched_student_id = face.student_id

        # Sort distances to find the best and second-best matches
        all_distances.sort(key=lambda x: x[0])

        # Adaptive threshold based on the distribution of distances
        if len(all_distances) > 1:
            best_distance = all_distances[0][0]
            second_best_distance = all_distances[1][0]

            # Dynamic threshold: if the best match is significantly better than the second best
            ratio_threshold = 0.9
            if second_best_distance > 0:
                distance_ratio = best_distance / second_best_distance
                is_unique_match = distance_ratio < ratio_threshold
            else:
                is_unique_match = True
        else:
            is_unique_match = True

        # Base threshold
        base_threshold = 0.8  # Adjusted threshold

        if min_distance <= base_threshold and is_unique_match and matched_student_id is not None:
            confidence = max(0, 1 - (min_distance / base_threshold))
            return jsonify({
                'success': True,
                'student_id': matched_student_id,
                'institute_id': institute_id,
                'confidence': round(confidence, 3),
                'distance': round(float(min_distance), 3),
                'threshold_used': base_threshold,
                'is_unique_match': is_unique_match
            }), 200
        else:
            return jsonify({
                'success': False,
                'message': 'No matching face found or match not unique enough',
                'distance': round(float(min_distance), 3),
                'threshold_used': base_threshold,
                'is_unique_match': is_unique_match
            }), 200

    except Exception as e:
        return jsonify({'error': f'Server error: {str(e)}'}), 500

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True, host='0.0.0.0', port=5000)
