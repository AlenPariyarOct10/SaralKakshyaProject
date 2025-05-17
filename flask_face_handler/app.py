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
from sklearn.model_selection import train_test_split
from sklearn.neighbors import KNeighborsClassifier
import joblib
import warnings
warnings.filterwarnings('ignore')

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
app.config['TRAINING_FOLDER'] = 'training_data'
app.config['MODEL_FOLDER'] = 'trained_models'
app.config['ALLOWED_EXTENSIONS'] = {'png', 'jpg', 'jpeg'}

# Create directories if they don't exist
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)
os.makedirs(app.config['TRAINING_FOLDER'], exist_ok=True)
os.makedirs(app.config['MODEL_FOLDER'], exist_ok=True)

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
    is_training_sample = db.Column(db.Boolean, default=False)

    def __repr__(self):
        return f'<FaceData {self.student_id}>'

class FaceModel(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    institute_id = db.Column(db.Integer, nullable=False)
    model_path = db.Column(db.String(255), nullable=False)
    accuracy = db.Column(db.Float)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    parameters = db.Column(db.Text)

    def __repr__(self):
        return f'<FaceModel {self.institute_id}>'

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

def enhanced_face_detection(image):
    if image is None:
        return None

    # First try with standard parameters
    faces = face_cascade.detectMultiScale(
        image,
        scaleFactor=1.05,
        minNeighbors=6,
        minSize=(60, 60),
        flags=cv2.CASCADE_SCALE_IMAGE
    )

    if len(faces) == 0:
        # Try with more sensitive parameters if no face found
        faces = face_cascade.detectMultiScale(
            image,
            scaleFactor=1.02,
            minNeighbors=3,
            minSize=(30, 30),
            flags=cv2.CASCADE_SCALE_IMAGE
        )

    if len(faces) > 0:
        (x, y, w, h) = faces[0]
        # Add adaptive padding
        padding = int(max(w, h) * 0.15)
        x = max(0, x - padding)
        y = max(0, y - padding)
        w = min(image.shape[1] - x, w + 2*padding)
        h = min(image.shape[0] - y, h + 2*padding)
        return image[y:y + h, x:x + w]
    return None

def advanced_preprocessing(image, target_size=(150, 150)):
    try:
        # CLAHE for adaptive histogram equalization
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
        eq_img = clahe.apply(image)

        # Bilateral filter for noise reduction while preserving edges
        filtered = cv2.bilateralFilter(eq_img, 9, 75, 75)

        # Resize with high-quality interpolation
        resized = cv2.resize(filtered, target_size, interpolation=cv2.INTER_CUBIC)

        # Local contrast enhancement
        lab = cv2.cvtColor(cv2.cvtColor(resized, cv2.COLOR_GRAY2BGR), cv2.COLOR_BGR2LAB)
        l, a, b = cv2.split(lab)
        clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8, 8))
        cl = clahe.apply(l)
        limg = cv2.merge((cl, a, b))
        enhanced = cv2.cvtColor(limg, cv2.COLOR_LAB2BGR)
        enhanced = cv2.cvtColor(enhanced, cv2.COLOR_BGR2GRAY)

        return enhanced
    except Exception as e:
        logger.error(f"Error in advanced preprocessing: {str(e)}")
        return None

def compute_enhanced_lbp_features(image, grid_size=(8, 8), radius=2, neighbors=16):
    if image is None:
        return None

    h, w = image.shape
    gh, gw = grid_size
    bh, bw = h // gh, w // gw

    if bh == 0 or bw == 0:
        logger.warning(f"Image too small for grid size {grid_size}")
        return None

    histograms = []

    # Create circular LBP pattern
    angles = 2 * np.pi * np.arange(neighbors) / neighbors
    x_offsets = radius * np.cos(angles)
    y_offsets = -radius * np.sin(angles)

    for i in range(gh):
        for j in range(gw):
            y_start = i * bh
            y_end = (i + 1) * bh if i < gh - 1 else h
            x_start = j * bw
            x_end = (j + 1) * bw if j < gw - 1 else w

            block = image[y_start:y_end, x_start:x_end]

            if block.shape[0] < 2*radius+1 or block.shape[1] < 2*radius+1:
                continue

            lbp_block = np.zeros((block.shape[0] - 2*radius, block.shape[1] - 2*radius), dtype=np.uint8)

            for y in range(radius, block.shape[0] - radius):
                for x in range(radius, block.shape[1] - radius):
                    center = block[y, x]
                    code = 0

                    for n in range(neighbors):
                        # Get circular neighborhood coordinates
                        xn = x + int(round(x_offsets[n]))
                        yn = y + int(round(y_offsets[n]))

                        # Ensure coordinates are within bounds
                        xn = max(0, min(block.shape[1] - 1, xn))
                        yn = max(0, min(block.shape[0] - 1, yn))

                        code |= (block[yn, xn] >= center) << n

                    lbp_block[y - radius, x - radius] = code

            # Compute uniform LBP histogram (59 bins)
            hist = np.histogram(lbp_block, bins=range(0, neighbors + 2), range=(0, neighbors + 1))[0]
            hist = hist.astype("float")
            hist /= (hist.sum() + 1e-7)  # Normalize
            histograms.extend(hist)

    return histograms if histograms else None

def train_knn_model(institute_id, n_neighbors=3, test_size=0.2):
    try:
        # Get all face data for this institute
        face_data = FaceData.query.filter_by(
            institute_id=institute_id,
            is_training_sample=True
        ).all()

        if len(face_data) < 5:
            return None, "At least 5 training samples are required"

        # Prepare data
        X = []
        y = []

        for face in face_data:
            try:
                hist = json.loads(face.histogram)
                X.append(hist)
                y.append(face.student_id)
            except json.JSONDecodeError:
                continue

        if len(X) < 5:
            return None, "Not enough valid training samples"

        # Split into train and test sets
        X_train, X_test, y_train, y_test = train_test_split(
            X, y, test_size=test_size, random_state=42
        )

        # Train KNN classifier
        knn = KNeighborsClassifier(
            n_neighbors=n_neighbors,
            weights='distance',
            metric='euclidean'
        )
        knn.fit(X_train, y_train)

        # Evaluate on test set
        accuracy = knn.score(X_test, y_test)

        # Save the trained model
        model_filename = f"knn_model_{institute_id}_{datetime.now().strftime('%Y%m%d_%H%M%S')}.pkl"
        model_path = os.path.join(app.config['MODEL_FOLDER'], model_filename)
        joblib.dump(knn, model_path)

        # Save model info to database
        model = FaceModel(
            institute_id=institute_id,
            model_path=model_path,
            accuracy=accuracy,
            parameters=json.dumps({
                'algorithm': 'KNN',
                'n_neighbors': n_neighbors,
                'test_size': test_size
            })
        )
        db.session.add(model)
        db.session.commit()

        return model, None
    except Exception as e:
        logger.error(f"Error training model: {str(e)}")
        return None, str(e)

def predict_with_model(institute_id, features):
    try:
        # Get the latest trained model for this institute
        model = FaceModel.query.filter_by(
            institute_id=institute_id
        ).order_by(FaceModel.created_at.desc()).first()

        if not model:
            return None, "No trained model available"

        # Load the model
        knn = joblib.load(model.model_path)

        # Make prediction
        proba = knn.predict_proba([features])[0]
        pred = knn.predict([features])[0]

        # Get confidence (probability of predicted class)
        confidence = np.max(proba)

        return {
            'student_id': int(pred),
            'confidence': float(confidence),
            'model_accuracy': float(model.accuracy)
        }, None
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        return None, str(e)

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
        is_training = data.get('is_training', False)

        # Validate input
        if not validate_ids(student_id, institute_id):
            return jsonify({'success': False, 'error': 'Invalid student or institute ID'}), 400

        if not images or len(images) < 1:
            return jsonify({'success': False, 'error': 'At least one image is required'}), 400

        # Check if face already exists
        existing_face = FaceData.query.filter_by(student_id=student_id).first()
        if existing_face and not is_training:
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
        face_image = enhanced_face_detection(gray_img)
        if face_image is None:
            return jsonify({'success': False, 'error': 'No face detected in image'}), 400

        # Preprocess and extract features
        processed_face = advanced_preprocessing(face_image)
        if processed_face is None:
            return jsonify({'success': False, 'error': 'Face preprocessing failed'}), 400

        hist = compute_enhanced_lbp_features(processed_face)
        if hist is None:
            return jsonify({'success': False, 'error': 'Feature extraction failed'}), 400

        # Store in database
        face_data = FaceData(
            student_id=student_id,
            institute_id=institute_id,
            histogram=json.dumps(hist),
            image_path=img_path,
            is_training_sample=is_training
        )

        db.session.add(face_data)
        db.session.commit()

        return jsonify({
            'success': True,
            'message': 'Face registered successfully',
            'image_path': img_path,
            'is_training_sample': is_training
        }), 200

    except SQLAlchemyError as e:
        db.session.rollback()
        logger.error(f"Database error: {str(e)}")
        return jsonify({'success': False, 'error': 'Database operation failed'}), 500
    except Exception as e:
        logger.error(f"Unexpected error: {str(e)}")
        return jsonify({'success': False, 'error': 'Internal server error'}), 500

@app.route('/train-model', methods=['POST'])
def train_model():
    try:
        data = request.json
        institute_id = data.get('institute_id')
        n_neighbors = data.get('n_neighbors', 3)
        test_size = data.get('test_size', 0.2)

        if not institute_id or not str(institute_id).isdigit():
            return jsonify({'success': False, 'error': 'Invalid institute ID'}), 400

        institute_id = int(institute_id)
        n_neighbors = int(n_neighbors)
        test_size = float(test_size)

        # Train the model
        model, error = train_knn_model(institute_id, n_neighbors, test_size)

        if error:
            return jsonify({'success': False, 'error': error}), 400

        return jsonify({
            'success': True,
            'model_id': model.id,
            'accuracy': model.accuracy,
            'parameters': json.loads(model.parameters)
        }), 200

    except Exception as e:
        logger.error(f"Training error: {str(e)}")
        return jsonify({'success': False, 'error': str(e)}), 500

@app.route('/recognize-face', methods=['POST'])
def recognize_face():
    try:
        data = request.json
        image_base64 = data.get('image')
        institute_id = data.get('institute_id')
        use_model = data.get('use_model', True)

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
        face_image = enhanced_face_detection(gray_img)
        if face_image is None:
            return jsonify({'success': False, 'error': 'No face detected in image'}), 400

        # Preprocess and extract features
        processed_face = advanced_preprocessing(face_image)
        if processed_face is None:
            return jsonify({'success': False, 'error': 'Face preprocessing failed'}), 400

        input_hist = compute_enhanced_lbp_features(processed_face)
        if input_hist is None:
            return jsonify({'success': False, 'error': 'Feature extraction failed'}), 400

        if use_model:
            # Use trained model for prediction
            prediction, error = predict_with_model(institute_id, input_hist)

            if error:
                return jsonify({'success': False, 'error': error}), 400

            return jsonify({
                'success': True,
                'student_id': prediction['student_id'],
                'confidence': prediction['confidence'],
                'model_accuracy': prediction['model_accuracy'],
                'method': 'model'
            }), 200
        else:
            # Fallback to direct comparison
            stored_faces = FaceData.query.filter_by(institute_id=institute_id).all()
            if not stored_faces:
                return jsonify({
                    'success': False,
                    'error': 'No face data available for this institute'
                }), 404

            min_distance = float('inf')
            matched_student_id = None
            matched_face = None

            for face in stored_faces:
                try:
                    stored_hist = json.loads(face.histogram)
                    distance = np.linalg.norm(np.array(input_hist) - np.array(stored_hist))

                    if distance < min_distance:
                        min_distance = distance
                        matched_student_id = face.student_id
                        matched_face = face
                except json.JSONDecodeError:
                    continue

            threshold = 0.5  # Adjusted based on enhanced features

            if min_distance <= threshold and matched_student_id is not None:
                confidence = min(1.0, max(0.0, 1 - (min_distance / threshold)))
                return jsonify({
                    'success': True,
                    'student_id': matched_student_id,
                    'confidence': round(confidence, 2),
                    'distance': round(min_distance, 4),
                    'method': 'direct'
                }), 200
            else:
                return jsonify({
                    'success': False,
                    'message': 'No matching face found',
                    'closest_distance': round(min_distance, 4),
                    'threshold': threshold
                }), 200

    except Exception as e:
        logger.error(f"Recognition error: {str(e)}")
        return jsonify({'success': False, 'error': str(e)}), 500

@app.route('/add-training-data', methods=['POST'])
def add_training_data():
    try:
        data = request.json
        student_id = data.get('student_id')
        institute_id = data.get('institute_id')
        images = data.get('images')

        if not validate_ids(student_id, institute_id):
            return jsonify({'success': False, 'error': 'Invalid student or institute ID'}), 400

        if not images:
            return jsonify({'success': False, 'error': 'No images provided'}), 400

        results = []

        for idx, img_data in enumerate(images):
            gray_img, img_path = decode_and_process_image(img_data, student_id, f"train_{idx}")
            if gray_img is None:
                results.append({'success': False, 'error': 'Failed to decode image'})
                continue

            face_image = enhanced_face_detection(gray_img)
            if face_image is None:
                results.append({'success': False, 'error': 'No face detected'})
                continue

            processed_face = advanced_preprocessing(face_image)
            if processed_face is None:
                results.append({'success': False, 'error': 'Preprocessing failed'})
                continue

            hist = compute_enhanced_lbp_features(processed_face)
            if hist is None:
                results.append({'success': False, 'error': 'Feature extraction failed'})
                continue

            face_data = FaceData(
                student_id=student_id,
                institute_id=institute_id,
                histogram=json.dumps(hist),
                image_path=img_path,
                is_training_sample=True
            )

            db.session.add(face_data)
            results.append({'success': True, 'image_path': img_path})

        db.session.commit()

        return jsonify({
            'success': True,
            'results': results,
            'total_added': sum(1 for r in results if r['success'])
        }), 200

    except Exception as e:
        db.session.rollback()
        logger.error(f"Error adding training data: {str(e)}")
        return jsonify({'success': False, 'error': str(e)}), 500

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True, host='0.0.0.0', port=5000)
