@extends('student-dashboard-layout')

@section('username', $user->fname . ' ' . $user->lname)

@section('styles')
    <style>
        .camera-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 0.5rem;
            aspect-ratio: 4/3;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1); /* Mirror effect */
        }

        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .face-guide {
            width: 200px;
            height: 200px;
            border: 2px dashed rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            box-shadow: 0 0 0 2000px rgba(0, 0, 0, 0.3);
        }

        .capture-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
        }

        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .photo-preview:hover {
            transform: scale(1.05);
        }

        .photo-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .photo-indicator.active {
            background-color: #0ea5e9;
            color: white;
        }

        .photo-indicator.completed {
            background-color: #10b981;
            color: white;
        }

        .photo-indicator.empty {
            background-color: #e5e7eb;
            color: #9ca3af;
        }

        .camera-container .loading-spinner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 30;
        }

        .camera-instructions {
            position: absolute;
            bottom: 80px;
            left: 0;
            width: 100%;
            text-align: center;
            color: white;
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
            z-index: 15;
        }

        .camera-feedback {
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 6px;
            border-radius: 4px;
            font-weight: 500;
            z-index: 20;
        }

        .camera-feedback.success {
            background-color: rgba(16, 185, 129, 0.8);
            color: white;
        }

        .camera-feedback.error {
            background-color: rgba(239, 68, 68, 0.8);
            color: white;
        }

        /* Animation for capture flash */
        @keyframes captureFlash {
            0% { background-color: rgba(255, 255, 255, 0); }
            50% { background-color: rgba(255, 255, 255, 0.8); }
            100% { background-color: rgba(255, 255, 255, 0); }
        }

        .capture-flash {
            animation: captureFlash 0.5s ease-out;
        }
    </style>
@endsection

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Face Recognition Setup</h1>
                <p class="text-gray-600 dark:text-gray-400">Capture 5 photos of your face to enable attendance through facial recognition</p>
            </div>

            <!-- Setup Progress -->
            <div class="card mb-8">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Setup Progress</h3>
                <div class="relative">
                    <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
                        <div id="progressBar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Getting Started</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Complete</span>
                    </div>
                </div>
            </div>

            <!-- Step 1: Instructions Card -->
            <div id="step1" class="card mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">1</div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Before You Begin</h3>
                        <ul class="mt-4 space-y-3 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Make sure you are in a well-lit environment</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Remove glasses, hats, or anything covering your face</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Position your face within the circular guide</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>You'll need to capture 5 photos from slightly different angles</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Your browser will ask for camera permission - please allow it</span>
                            </li>
                        </ul>
                        <button id="startSetupBtn" class="mt-6 btn-primary">
                            <i class="fas fa-camera mr-2"></i> Start Camera Setup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Camera and Capture -->
            <div id="step2" class="card mb-8 hidden">
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">2</div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Capture Your Photos</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Position your face in the circle and take 5 photos</p>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="camera-container">
                        <video id="video" autoplay playsinline muted></video>
                        <div class="camera-overlay">
                            <div class="face-guide"></div>
                        </div>
                        <div id="cameraInstructions" class="camera-instructions">
                            Position your face in the circle
                        </div>
                        <div id="cameraFeedback" class="camera-feedback hidden"></div>
                        <button id="captureBtn" class="capture-btn px-4 py-2 bg-white text-primary-600 rounded-full shadow-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <i class="fas fa-camera mr-1"></i> Capture
                        </button>
                        <div id="loadingSpinner" class="loading-spinner hidden">
                            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
                        </div>
                        <canvas id="canvas" class="hidden"></canvas>
                    </div>
                </div>

                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Captured Photos (0/5)</h4>
                    <div class="flex flex-wrap gap-4">
                        <!-- Photo placeholders -->
                        <div class="relative">
                            <div class="photo-preview bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 photo-indicator empty flex items-center justify-center">1</div>
                        </div>
                        <div class="relative">
                            <div class="photo-preview bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 photo-indicator empty flex items-center justify-center">2</div>
                        </div>
                        <div class="relative">
                            <div class="photo-preview bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 photo-indicator empty flex items-center justify-center">3</div>
                        </div>
                        <div class="relative">
                            <div class="photo-preview bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 photo-indicator empty flex items-center justify-center">4</div>
                        </div>
                        <div class="relative">
                            <div class="photo-preview bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 photo-indicator empty flex items-center justify-center">5</div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button id="backToStep1Btn" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button id="goToStep3Btn" class="btn-primary opacity-50 cursor-not-allowed" disabled>
                        Continue <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Confirmation and Submission -->
            <div id="step3" class="card mb-8 hidden">
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">3</div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Review & Submit</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Review your photos and submit them for processing</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Your Captured Photos</h4>
                    <div id="photoReviewContainer" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <!-- Photos will be inserted here dynamically -->
                    </div>
                </div>

                <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-md mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">What happens next?</h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <p>Your face images will be securely processed to create a unique biometric template. This template will be used for attendance verification only and is stored securely.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button id="backToStep2Btn" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button id="submitBtn" class="btn-primary">
                        <i class="fas fa-check mr-2"></i> Submit Photos
                    </button>
                </div>
            </div>

            <!-- Step 4: Success -->
            <div id="step4" class="card text-center hidden">
                <div class="w-20 h-20 mx-auto mb-4 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-500 dark:text-green-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Setup Complete!</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Your face recognition setup has been successfully completed. You can now use facial recognition for attendance.</p>
                <a href="{{ route('student.attendance') }}" class="btn-primary inline-block">
                    <i class="fas fa-calendar-check mr-2"></i> View My Attendance
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Global variables
        let currentStep = 1;
        let stream = null;
        let capturedPhotos = [];
        let currentPhotoIndex = 0;
        let processing = false;

        // DOM elements
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const step4 = document.getElementById('step4');
        const progressBar = document.getElementById('progressBar');
        const startSetupBtn = document.getElementById('startSetupBtn');
        const backToStep1Btn = document.getElementById('backToStep1Btn');
        const backToStep2Btn = document.getElementById('backToStep2Btn');
        const goToStep3Btn = document.getElementById('goToStep3Btn');
        const submitBtn = document.getElementById('submitBtn');
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const cameraInstructions = document.getElementById('cameraInstructions');
        const cameraFeedback = document.getElementById('cameraFeedback');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const photoReviewContainer = document.getElementById('photoReviewContainer');

        // Update progress bar
        function updateProgress(step) {
            const progress = (step - 1) * 33.3; // 3 steps, each 33.3%
            progressBar.style.width = `${progress}%`;
        }

        // Show a specific step
        function showStep(step) {
            step1.classList.add('hidden');
            step2.classList.add('hidden');
            step3.classList.add('hidden');
            step4.classList.add('hidden');

            if (step === 1) step1.classList.remove('hidden');
            if (step === 2) step2.classList.remove('hidden');
            if (step === 3) step3.classList.remove('hidden');
            if (step === 4) step4.classList.remove('hidden');

            currentStep = step;
            updateProgress(step);
        }

        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: "user"
                    }
                });
                video.srcObject = stream;
                return true;
            } catch (err) {
                showFeedback(`Camera error: ${err.message}`, 'error');
                console.error('Error accessing camera:', err);
                return false;
            }
        }

        // Stop camera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        // Capture photo
        function capturePhoto() {
            if (processing) return;

            processing = true;
            loadingSpinner.classList.remove('hidden');

            // Create flash effect
            const flash = document.createElement('div');
            flash.className = 'absolute inset-0 bg-white bg-opacity-0 capture-flash';
            document.querySelector('.camera-container').appendChild(flash);

            setTimeout(() => {
                flash.remove();

                // Capture from video
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Get image data
                const imageData = canvas.toDataURL('image/jpeg');

                // Simulate face detection (would be done on the server in a real app)
                setTimeout(() => {
                    // 80% chance of successful face detection for demo purposes
                    const faceDetected = Math.random() < 0.8;

                    if (faceDetected) {
                        // Add to captured photos array
                        capturedPhotos.push(imageData);
                        currentPhotoIndex = capturedPhotos.length;

                        // Update UI
                        updateCapturedPhotosUI();
                        showFeedback('Face captured successfully!', 'success');

                        // Check if all photos captured
                        if (capturedPhotos.length === 5) {
                            goToStep3Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            goToStep3Btn.disabled = false;
                            showFeedback('All photos captured! Click Continue.', 'success');
                        } else {
                            // Update instructions for next photo
                            updateCaptureInstructions();
                        }
                    } else {
                        showFeedback('Face not detected clearly. Please try again.', 'error');
                    }

                    processing = false;
                    loadingSpinner.classList.add('hidden');
                }, 1500); // Simulate processing time
            }, 300);
        }

        // Update capture instructions based on current photo index
        function updateCaptureInstructions() {
            const instructions = [
                'Position your face in the circle',
                'Tilt your head slightly to the right',
                'Tilt your head slightly to the left',
                'Tilt your head slightly upward',
                'Tilt your head slightly downward'
            ];

            cameraInstructions.textContent = instructions[currentPhotoIndex];
        }

        // Show feedback message
        function showFeedback(message, type) {
            cameraFeedback.textContent = message;
            cameraFeedback.className = `camera-feedback ${type}`;
            cameraFeedback.classList.remove('hidden');

            setTimeout(() => {
                cameraFeedback.classList.add('hidden');
            }, 3000);
        }

        // Update the UI to show captured photos
        function updateCapturedPhotosUI() {
            const photoContainers = document.querySelectorAll('.photo-preview');
            const indicators = document.querySelectorAll('.photo-indicator');

            // Update the header text
            document.querySelector('h4').textContent = `Captured Photos (${capturedPhotos.length}/5)`;

            // Update each photo container
            capturedPhotos.forEach((photo, index) => {
                photoContainers[index].innerHTML = '';
                photoContainers[index].style.backgroundImage = `url(${photo})`;
                photoContainers[index].style.backgroundSize = 'cover';
                photoContainers[index].style.backgroundPosition = 'center';

                indicators[index].classList.remove('empty');
                indicators[index].classList.add('completed');
            });
        }

        // Prepare the review screen
        function prepareReviewScreen() {
            photoReviewContainer.innerHTML = '';

            capturedPhotos.forEach((photo, index) => {
                const photoElement = document.createElement('div');
                photoElement.className = 'relative';
                photoElement.innerHTML = `
                <img src="${photo}" alt="Captured face ${index + 1}" class="w-full h-32 object-cover rounded-lg shadow-sm">
                <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-primary-500 text-white flex items-center justify-center text-xs font-bold">${index + 1}</div>
            `;
                photoReviewContainer.appendChild(photoElement);
            });
        }

        // Submit photos to the server
        function submitPhotos() {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';

            // Prepare the data
            const formData = new FormData();
            capturedPhotos.forEach((photo, index) => {
                // Convert base64 to blob
                const byteString = atob(photo.split(',')[1]);
                const mimeString = photo.split(',')[0].split(':')[1].split(';')[0];
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                const blob = new Blob([ab], { type: mimeString });
                formData.append(`photo_${index + 1}`, blob, `photo_${index + 1}.jpg`);
            });

            // Add CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            // Send to server
            fetch('{{ route("student.saveFacePhotos") }}', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success screen
                        showStep(4);
                        progressBar.style.width = '100%';
                    } else {
                        alert('Error: ' + data.message);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Submit Photos';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Submit Photos';
                });
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Start setup button
            startSetupBtn.addEventListener('click', async function() {
                const cameraStarted = await startCamera();
                if (cameraStarted) {
                    showStep(2);
                    updateCaptureInstructions();
                }
            });

            // Back buttons
            backToStep1Btn.addEventListener('click', function() {
                showStep(1);
                stopCamera();
            });

            backToStep2Btn.addEventListener('click', async function() {
                const cameraStarted = await startCamera();
                if (cameraStarted) {
                    showStep(2);
                    updateCaptureInstructions();
                }
            });

            // Capture button
            captureBtn.addEventListener('click', capturePhoto);

            // Next step button
            goToStep3Btn.addEventListener('click', function() {
                if (capturedPhotos.length === 5) {
                    stopCamera();
                    showStep(3);
                    prepareReviewScreen();
                }
            });

            // Submit button
            submitBtn.addEventListener('click', submitPhotos);
        });
    </script>
@endsection
