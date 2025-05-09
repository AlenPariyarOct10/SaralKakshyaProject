import os
import cv2
import numpy as np
import base64
import json
from datetime import datetime
from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from sqlalchemy.exc import SQLAlchemyError
import logging
from werkzeug.utils import secure_filename

# Initialize Flask app
app = Flask(__name__)
CORS(app)

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Database configuration
app.config['SQLALCHEMY_DATABASE_URI'] = os.getenv('DATABASE_URI', 'mysql+pymysql://root:@localhost:3306/saralkakshyaproject_face_db')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['UPLOAD_FOLDER'] = 'face_images'
app.config['ALLOWED_EXTENSIONS'] = {'png', 'jpg', 'jpeg'}

# Create directories if they don't exist
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)

# Initialize database
db = SQLAlchemy(app)

# Load Haar Cascade for face detection
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
if face_cascade.empty():
    logger.error("Failed to load Haar Cascade classifier")
    exit(1)

# ---------- DATABASE MODELS ----------
class FaceData(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    student_id = db.Column(db.Integer, unique=True, nullable=False)
    institute_id = db.Column(db.Integer, nullable=False)
    histogram = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    image_path = db.Column(db.String(255))

    def __repr__(self):
        return f'<FaceData {self.student_id}>'

# ---------- UTILITY FUNCTIONS ----------
def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in app.config['ALLOWED_EXTENSIONS']

def save_image_file(image, student_id, prefix=""):
    try:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"{prefix}_{student_id}_{timestamp}.jpg"
        filename = secure_filename(filename)
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        cv2.imwrite(filepath, image)
        return filepath
    except Exception as e:
        logger.error(f"Error saving image: {str(e)}")
        return None

def decode_and_process_image(base64_str, student_id=None, purpose="register"):
    try:
        if ',' in base64_str:
            base64_str = base64_str.split(',')[1]

        img_data = base64.b64decode(base64_str)
        np_arr = np.frombuffer(img_data, np.uint8)
        color_img = cv2.imdecode(np_arr, cv2.IMREAD_COLOR)

        # Save the original color image
        img_path = save_image_file(color_img, student_id, purpose) if student_id else None

        # Convert to grayscale for processing
        gray_img = cv2.cvtColor(color_img, cv2.COLOR_BGR2GRAY)
        return gray_img, img_path
    except Exception as e:
        logger.error(f"Error decoding image: {str(e)}")
        return None, None

def detect_and_crop_face(image):
    if image is None:
        return None

    # Enhanced face detection parameters
    faces = face_cascade.detectMultiScale(
        image,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(50, 50),
        flags=cv2.CASCADE_SCALE_IMAGE
    )

    if len(faces) > 0:
        (x, y, w, h) = faces[0]
        # Add padding around the detected face
        padding = int(w * 0.2)
        x = max(0, x - padding)
        y = max(0, y - padding)
        w = min(image.shape[1] - x, w + 2*padding)
        h = min(image.shape[0] - y, h + 2*padding)
        return image[y:y + h, x:x + w]
    return None

def preprocess_face(image, target_size=(100, 100)):
    try:
        # Apply histogram equalization for better contrast
        eq_img = cv2.equalizeHist(image)
        # Resize with anti-aliasing
        resized = cv2.resize(eq_img, target_size, interpolation=cv2.INTER_AREA)
        # Apply Gaussian blur to reduce noise
        blurred = cv2.GaussianBlur(resized, (3, 3), 0)
        return blurred
    except Exception as e:
        logger.error(f"Error preprocessing face: {str(e)}")
        return None

def compute_lbp_features(image, grid_size=(8, 8)):
    if image is None:
        return None

    h, w = image.shape
    gh, gw = grid_size
    bh, bw = h // gh, w // gw

    if bh == 0 or bw == 0:
        logger.warning(f"Image too small for grid size {grid_size}")
        return None

    histograms = []

    for i in range(gh):
        for j in range(gw):
            # Handle edge blocks
            y_start = i * bh
            y_end = (i + 1) * bh if i < gh - 1 else h
            x_start = j * bw
            x_end = (j + 1) * bw if j < gw - 1 else w

            block = image[y_start:y_end, x_start:x_end]

            # Skip blocks that are too small for LBP
            if block.shape[0] < 3 or block.shape[1] < 3:
                continue

            # Compute LBP for the block
            lbp_block = np.zeros((block.shape[0] - 2, block.shape[1] - 2), dtype=np.uint8)
            for y in range(1, block.shape[0] - 1):
                for x in range(1, block.shape[1] - 1):
                    center = block[y, x]
                    code = 0
                    code |= (block[y-1, x-1] > center) << 7
                    code |= (block[y-1, x] > center) << 6
                    code |= (block[y-1, x+1] > center) << 5
                    code |= (block[y, x+1] > center) << 4
                    code |= (block[y+1, x+1] > center) << 3
                    code |= (block[y+1, x] > center) << 2
                    code |= (block[y+1, x-1] > center) << 1
                    code |= (block[y, x-1] > center) << 0
                    lbp_block[y-1, x-1] = code

            # Compute histogram for the block
            hist, _ = np.histogram(lbp_block.ravel(), bins=256, range=(0, 256))
            hist = hist.astype("float")
            hist /= (hist.sum() + 1e-7)  # Normalize
            histograms.extend(hist.tolist())

    return histograms if histograms else None

def calculate_similarity(hist1, hist2):
    if hist1 is None or hist2 is None:
        return float('inf')

    # Convert to numpy arrays
    h1 = np.array(hist1)
    h2 = np.array(hist2)

    # Handle different length histograms
    min_len = min(len(h1), len(h2))

    # Calculate Euclidean distance
    return np.sqrt(np.sum((h1[:min_len] - h2[:min_len]) ** 2))

def validate_ids(student_id, institute_id):
    try:
        student_id = int(student_id)
        institute_id = int(institute_id)
        return student_id > 0 and institute_id > 0
    except (ValueError, TypeError):
        return False

# ---------- FLASK ROUTES ----------
@app.route('/register-face', methods=['POST'])
def register_face():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        images = data.get('images')

        # Validate input
        if not validate_ids(student_id, institute_id):
            return jsonify({'success': False, 'error': 'Invalid student or institute ID'}), 400

        if not images or len(images) < 1:
            return jsonify({'success': False, 'error': 'At least one image is required'}), 400

        # Check if face already exists
        existing_face = FaceData.query.filter_by(student_id=student_id).first()
        if existing_face:
            return jsonify({
                'success': False,
                'error': 'Face data already exists for this student',
                'exists': True
            }), 409

        # Process the first image
        base64_img = images[0]
        gray_img, img_path = decode_and_process_image(base64_img, student_id, "reg")
        if gray_img is None:
            return jsonify({'success': False, 'error': 'Failed to decode image'}), 400

        # Detect and crop face
        face_image = detect_and_crop_face(gray_img)
        if face_image is None:
            return jsonify({'success': False, 'error': 'No face detected in image'}), 400

        # Preprocess and extract features
        processed_face = preprocess_face(face_image)
        if processed_face is None:
            return jsonify({'success': False, 'error': 'Face preprocessing failed'}), 400

        hist = compute_lbp_features(processed_face)
        if hist is None:
            return jsonify({'success': False, 'error': 'Feature extraction failed'}), 400

        # Store in database
        face_data = FaceData(
            student_id=student_id,
            institute_id=institute_id,
            histogram=json.dumps(hist),
            image_path=img_path
        )

        db.session.add(face_data)
        db.session.commit()

        return jsonify({
            'success': True,
            'message': 'Face registered successfully',
            'image_path': img_path
        }), 200

    except SQLAlchemyError as e:
        db.session.rollback()
        logger.error(f"Database error: {str(e)}")
        return jsonify({'success': False, 'error': 'Database operation failed'}), 500
    except Exception as e:
        logger.error(f"Unexpected error: {str(e)}")
        return jsonify({'success': False, 'error': 'Internal server error'}), 500

@app.route('/update-face', methods=['POST'])
def update_face():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        images = data.get('images')

        # Validate input
        if not validate_ids(student_id, institute_id):
            return jsonify({'success': False, 'error': 'Invalid student or institute ID'}), 400

        if not images or len(images) < 1:
            return jsonify({'success': False, 'error': 'At least one image is required'}), 400

        # Check if face exists
        existing_face = FaceData.query.filter_by(student_id=student_id).first()
        if not existing_face:
            return jsonify({
                'success': False,
                'error': 'Student not found in database'
            }), 404

        # Process the first image
        base64_img = images[0]
        gray_img, img_path = decode_and_process_image(base64_img, student_id, "upd")
        if gray_img is None:
            return jsonify({'success': False, 'error': 'Failed to decode image'}), 400

        # Detect and crop face
        face_image = detect_and_crop_face(gray_img)
        if face_image is None:
            return jsonify({'success': False, 'error': 'No face detected in image'}), 400

        # Preprocess and extract features
        processed_face = preprocess_face(face_image)
        if processed_face is None:
            return jsonify({'success': False, 'error': 'Face preprocessing failed'}), 400

        hist = compute_lbp_features(processed_face)
        if hist is None:
            return jsonify({'success': False, 'error': 'Feature extraction failed'}), 400

        # Update database record
        existing_face.histogram = json.dumps(hist)
        existing_face.institute_id = institute_id
        existing_face.image_path = img_path if img_path else existing_face.image_path
        existing_face.created_at = datetime.utcnow()

        db.session.commit()

        return jsonify({
            'success': True,
            'message': 'Face data updated successfully',
            'image_path': existing_face.image_path
        }), 200

    except SQLAlchemyError as e:
        db.session.rollback()
        logger.error(f"Database error: {str(e)}")
        return jsonify({'success': False, 'error': 'Database operation failed'}), 500
    except Exception as e:
        logger.error(f"Unexpected error: {str(e)}")
        return jsonify({'success': False, 'error': 'Internal server error'}), 500

@app.route('/has-face', methods=['POST'])
def face_exists():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')

        # Validate input
        if not validate_ids(student_id, institute_id):
            return jsonify({'success': False, 'error': 'Invalid student or institute ID'}), 400

        # Check database
        face_data = FaceData.query.filter_by(
            student_id=student_id,
            institute_id=institute_id
        ).first()

        return jsonify({
            'success': True,
            'exists': bool(face_data),
            'has_image': bool(face_data and face_data.image_path)
        }), 200

    except Exception as e:
        logger.error(f"Error checking face existence: {str(e)}")
        return jsonify({'success': False, 'error': 'Internal server error'}), 500

@app.route('/recognize-face', methods=['POST'])
def recognize_face():
    try:
        data = request.json
        image_base64 = data.get('image')
        institute_id = data.get('institute_id')

        # Validate input
        if not image_base64:
            return jsonify({'success': False, 'error': 'Missing image data'}), 400

        if not institute_id or not str(institute_id).isdigit():
            return jsonify({'success': False, 'error': 'Invalid institute ID'}), 400

        institute_id = int(institute_id)

        # Process input image
        gray_img, _ = decode_and_process_image(image_base64, purpose="recognition")
        if gray_img is None:
            return jsonify({'success': False, 'error': 'Failed to decode image'}), 400

        # Detect and crop face
        face_image = detect_and_crop_face(gray_img)
        if face_image is None:
            return jsonify({'success': False, 'error': 'No face detected in image'}), 400

        # Preprocess and extract features
        processed_face = preprocess_face(face_image)
        if processed_face is None:
            return jsonify({'success': False, 'error': 'Face preprocessing failed'}), 400

        input_hist = compute_lbp_features(processed_face)
        if input_hist is None:
            return jsonify({'success': False, 'error': 'Feature extraction failed'}), 400

        # Get all faces from the institute
        stored_faces = FaceData.query.filter_by(institute_id=institute_id).all()
        if not stored_faces:
            return jsonify({
                'success': False,
                'error': 'No face data available for this institute'
            }), 404

        # Find the best match
        min_distance = float('inf')
        matched_student_id = None
        matched_face = None

        for face in stored_faces:
            try:
                stored_hist = json.loads(face.histogram)
                distance = calculate_similarity(input_hist, stored_hist)

                logger.debug(f"Comparing with student {face.student_id}: distance={distance:.4f}")

                if distance < min_distance:
                    min_distance = distance
                    matched_student_id = face.student_id
                    matched_face = face
            except json.JSONDecodeError:
                logger.warning(f"Invalid histogram data for student {face.student_id}")
                continue

        # Recognition threshold (adjust based on your testing)
        threshold = 2

        if min_distance <= threshold and matched_student_id is not None:
            # Calculate confidence (0-1 scale)
            confidence = min(1.0, max(0.0, 1 - (min_distance / threshold)))

            logger.info(f"Face recognized: student_id={matched_student_id}, confidence={confidence:.2f}, distance={min_distance:.4f}")

            return jsonify({
                'success': True,
                'student_id': matched_student_id,
                'confidence': round(confidence, 2),
                'distance': round(min_distance, 4),
                'image_path': matched_face.image_path if matched_face else None
            }), 200
        else:
            logger.info(f"No match found. Closest distance: {min_distance:.4f} (threshold: {threshold})")
            return jsonify({
                'success': False,
                'message': 'No matching face found',
                'closest_distance': round(min_distance, 4),
                'threshold': threshold
            }), 200

    except Exception as e:
        logger.error(f"Recognition error: {str(e)}")
        return jsonify({'success': False, 'error': 'Internal server error'}), 500

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True, host='0.0.0.0', port=5000)
