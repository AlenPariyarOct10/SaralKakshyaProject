import os
import cv2
import numpy as np
import base64
import json
from datetime import datetime
from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = 'face_data'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:@localhost:3306/saralkakshyaproject_face_db'

app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)

# Load Haar Cascade for face detection
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')


# ---------- DATABASE MODELS ----------
class FaceData(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    student_id = db.Column(db.Integer, unique=True, nullable=False)
    histogram = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)


# ---------- UTILITY FUNCTION ----------
def decode_and_save_image(base64_str, path):
    img_data = base64.b64decode(base64_str)
    np_arr = np.frombuffer(img_data, np.uint8)
    img = cv2.imdecode(np_arr, cv2.IMREAD_GRAYSCALE)
    return img


def crop_face(image):
    # Detect faces in the image
    faces = face_cascade.detectMultiScale(image, scaleFactor=1.1, minNeighbors=5)
    if len(faces) > 0:
        # Take the first detected face
        (x, y, w, h) = faces[0]
        return image[y:y + h, x:x + w]  # Crop the face
    return image  # Return the original image if no face is detected


def lbp_histogram(image):
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
    hist /= (hist.sum() + 1e-7)  # normalize
    return hist.tolist()


def compare_histograms(hist1, hist2):
    # Calculate the distance between two histograms
    return np.sqrt(np.sum((np.array(hist1) - np.array(hist2)) ** 2))


# ---------- FLASK ROUTE ----------
@app.route('/register-face', methods=['POST'])
def register_face():
    data = request.json
    student_id = data.get('student_id')
    images = data.get('images')  # list of 5 base64 images

    if not student_id or not images or len(images) != 5:
        return jsonify({'error': 'Missing or invalid data'}), 400

    student_folder = os.path.join(app.config['UPLOAD_FOLDER'], f"student_{student_id}")
    os.makedirs(student_folder, exist_ok=True)

    all_histograms = []
    saved_paths = []

    for idx, base64_img in enumerate(images):
        filename = f"{idx + 1}.png"
        full_path = os.path.join(student_folder, filename)
        img = decode_and_save_image(base64_img, full_path)

        # Crop the face and compute the LBP histogram
        face_image = crop_face(img)
        hist = lbp_histogram(face_image)
        all_histograms.append(hist)
        saved_paths.append(full_path)

    # Calculate the average histogram for the student (mean of all 5 histograms)
    avg_histogram = np.mean(all_histograms, axis=0).tolist()

    # Save the face data (student_id and histogram) to the database
    face_data = FaceData(student_id=student_id, histogram=json.dumps(avg_histogram))
    db.session.add(face_data)
    db.session.commit()

    return jsonify({'message': 'Face registered successfully'}), 200


@app.route('/recognize-face', methods=['POST'])
def recognize_face():
    data = request.json
    image_base64 = data.get('image')  # Base64 image for recognition

    if not image_base64:
        return jsonify({'error': 'Missing image data'}), 400

    # Decode the image and crop the face
    img = decode_and_save_image(image_base64, 'temp_image.png')
    face_image = crop_face(img)

    # Compute the LBP histogram for the input face image
    input_hist = lbp_histogram(face_image)

    # Query the database for stored face data
    stored_faces = FaceData.query.all()
    min_distance = float('inf')
    matched_student_id = None

    # Compare the input histogram with stored histograms
    for face in stored_faces:
        stored_hist = json.loads(face.histogram)
        distance = compare_histograms(input_hist, stored_hist)

        if distance < min_distance:
            min_distance = distance
            matched_student_id = face.student_id

    # Check if a match was found
    if matched_student_id:
        return jsonify({'status': 'success', 'student_id': matched_student_id}), 200
    else:
        return jsonify({'status': 'failure', 'student_id': '-1'}), 404


if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True)
