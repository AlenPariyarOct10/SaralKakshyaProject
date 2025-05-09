@extends('backend.layout.student-dashboard-layout')

@section('username', $user->fname . ' ' . $user->lname)

@section('styles')
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .dark .card {
            background: #1f2937;
            color: #f3f4f6;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            margin-top: 15px;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        #video {
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #000;
        }

        .capture-btn {
            background-color: #ef4444;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .capture-btn:hover {
            background-color: #dc2626;
        }

        #capturedPhotosContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }

        #capturedPhotosContainer img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            border: 2px solid #e5e7eb;
        }

        #photoReviewContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        #photoReviewContainer img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            border: 2px solid #e5e7eb;
        }

        .hidden {
            display: none;
        }

        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #3b82f6;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Face Recognition Setup</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Capture 5 photos of your face to enable attendance through facial recognition.
                </p>
            </div>

            <!-- Warning for Overriding -->
            <div id="overridingWarning" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 mb-6 rounded relative hidden" role="alert">
                <strong class="font-bold">Alert!</strong>
                <span class="block sm:inline">
                    You have already configured face recognition setup. If you proceed, the existing setup will be overwritten.
                </span>
            </div>

            <!-- Steps and Forms -->
            <div id="setup-steps">
                <!-- Step 1: Instructions -->
                <div id="step1" class="card mb-8">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Instructions</h3>
                    <ul class="list-disc pl-5 space-y-2 mb-6">
                        <li>Make sure you are in a well-lit environment.</li>
                        <li>Remove glasses, hats, or coverings.</li>
                        <li>Position your face within the guide.</li>
                        <li>Keep a neutral expression for best results.</li>
                        <li>Ensure your entire face is visible.</li>
                    </ul>
                    <button id="startSetupBtn" class="btn-primary">
                        Start Camera Setup
                    </button>
                </div>

                <!-- Step 2: Capture Photos -->
                <div id="step2" class="card mb-8 hidden">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Capture Photos</h3>
                    <p class="mb-4 text-gray-600 dark:text-gray-400">Please position your face in the center of the frame.</p>
                    <div class="relative">
                        <video id="video" autoplay playsinline muted></video>
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none">
                            <div class="border-2 border-white rounded-full w-64 h-64 opacity-50"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <button id="captureBtn" class="capture-btn">Capture Photo</button>
                        <span id="photoCounter" class="text-gray-600 dark:text-gray-400">0/5 photos captured</span>
                    </div>
                    <div id="capturedPhotosContainer" class="mt-6"></div>
                    <button id="goToStep3Btn" class="btn-primary hidden mt-4">Continue to Review</button>
                </div>

                <!-- Step 3: Review and Submit -->
                <div id="step3" class="card mb-8 hidden">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Review Your Photos</h3>
                    <p class="mb-4 text-gray-600 dark:text-gray-400">Please review your captured photos before submission.</p>
                    <div id="photoReviewContainer"></div>
                    <div class="flex space-x-4 mt-6">
                        <button id="backToStep2Btn" class="btn-primary bg-gray-500 hover:bg-gray-600">Retake Photos</button>
                        <button id="submitBtn" class="btn-primary">Submit Photos</button>
                    </div>
                    <div id="loadingIndicator" class="mt-4 hidden">
                        <div class="loading-spinner"></div>
                        <p class="text-center mt-2">Processing your photos...</p>
                    </div>
                </div>

                <!-- Step 4: Success -->
                <div id="step4" class="card text-center hidden">
                    <div class="mb-4 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Setup Complete!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        You can now use facial recognition for attendance.
                    </p>
                    <a href="{{ route('student.dashboard') }}" class="btn-primary inline-block">Return to Dashboard</a>
                </div>

                <!-- Step 5: Error -->
                <div id="step5" class="card text-center hidden">
                    <div class="mb-4 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Setup Failed</h3>
                    <p id="errorMessage" class="text-gray-600 dark:text-gray-400 mb-6">
                        There was an error processing your photos.
                    </p>
                    <button id="tryAgainBtn" class="btn-primary">Try Again</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Global variables
        let stream = null;
        let capturedPhotos = [];
        let overriding = false;

        // DOM elements
        const video = document.getElementById('video');
        const captureBtn = document.getElementById('captureBtn');
        const goToStep3Btn = document.getElementById('goToStep3Btn');
        const submitBtn = document.getElementById('submitBtn');
        const backToStep2Btn = document.getElementById('backToStep2Btn');
        const tryAgainBtn = document.getElementById('tryAgainBtn');
        const photoCounter = document.getElementById('photoCounter');
        const overridingWarning = document.getElementById('overridingWarning');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');

        // Check if user already has face data
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('http://127.0.0.1:5000/has-face', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_id: {{ $user->id }},
                        institute_id: {{ $user->institute_id ?? 0 }}
                    })
                });

                const data = await response.json();
                if (data.exists) {
                    overriding = true;
                    overridingWarning.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error checking face data:', error);
            }
        });

        // Show step function
        function showStep(stepNumber) {
            document.querySelectorAll('#setup-steps > div').forEach(step => {
                step.classList.add('hidden');
            });
            document.getElementById(`step${stepNumber}`).classList.remove('hidden');
        }

        // Start Camera Setup
        document.getElementById('startSetupBtn').addEventListener('click', async function() {
            try {
                await startCamera();
                showStep(2);
            } catch (error) {
                console.error('Could not start camera:', error);
                errorMessage.textContent = 'Could not access camera. Please check permissions.';
                showStep(5);
            }
        });

        // Start Camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                });
                video.srcObject = stream;
            } catch (err) {
                console.error('Error accessing camera:', err);
                throw err;
            }
        }

        // Stop Camera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
                stream = null;
            }
        }

        // Capture Photo
        captureBtn.addEventListener('click', function() {
            if (capturedPhotos.length >= 5) {
                alert("You've already captured the maximum of 5 photos!");
                return;
            }

            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');

            // Draw the video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert to JPEG with quality 0.8 (80%)
            const photo = canvas.toDataURL('image/jpeg', 0.8);
            capturedPhotos.push(photo);

            updateCapturedPhotosUI();
            updatePhotoCounter();

            if (capturedPhotos.length === 5) {
                goToStep3Btn.classList.remove('hidden');
            }
        });

        // Update Captured Photos UI
        function updateCapturedPhotosUI() {
            const container = document.getElementById('capturedPhotosContainer');
            container.innerHTML = '';

            capturedPhotos.forEach((photo, index) => {
                const img = document.createElement('img');
                img.src = photo;
                img.alt = `Captured Photo ${index + 1}`;
                img.className = 'object-cover';
                container.appendChild(img);
            });
        }

        // Update photo counter
        function updatePhotoCounter() {
            photoCounter.textContent = `${capturedPhotos.length}/5 photos captured`;
        }

        // Go to Step 3 (Review)
        goToStep3Btn.addEventListener('click', function() {
            const reviewContainer = document.getElementById('photoReviewContainer');
            reviewContainer.innerHTML = '';

            capturedPhotos.forEach((photo, index) => {
                const div = document.createElement('div');
                div.className = 'flex flex-col items-center';

                const img = document.createElement('img');
                img.src = photo;
                img.alt = `Review Photo ${index + 1}`;
                img.className = 'mb-2';

                const label = document.createElement('span');
                label.textContent = `Photo ${index + 1}`;
                label.className = 'text-sm text-gray-600 dark:text-gray-400';

                div.appendChild(img);
                div.appendChild(label);
                reviewContainer.appendChild(div);
            });

            showStep(3);
            stopCamera();
        });

        // Back to Step 2 (Retake photos)
        backToStep2Btn.addEventListener('click', function() {
            capturedPhotos = [];
            showStep(2);
            startCamera();
        });

        // Try again button
        tryAgainBtn.addEventListener('click', function() {
            capturedPhotos = [];
            showStep(1);
        });

        // Submit Photos
        submitBtn.addEventListener('click', async function() {
            if (capturedPhotos.length < 5) {
                alert('Please capture all 5 photos before submitting.');
                return;
            }

            submitBtn.disabled = true;
            loadingIndicator.classList.remove('hidden');

            try {
                const endpoint = overriding ? 'http://127.0.0.1:5000/update-face' : 'http://127.0.0.1:5000/register-face';

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_id: {{ $user->id }},
                        institute_id: {{ $user->institute_id ?? 0 }},
                        images: capturedPhotos
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showStep(4);
                } else {
                    errorMessage.textContent = data.error || 'Failed to submit photos. Please try again.';
                    showStep(5);
                }
            } catch (err) {
                console.error('Submission error:', err);
                errorMessage.textContent = 'Network error. Please check your connection and try again.';
                showStep(5);
            } finally {
                submitBtn.disabled = false;
                loadingIndicator.classList.add('hidden');
            }
        });

        // Clean up camera when leaving the page
        window.addEventListener('beforeunload', function() {
            stopCamera();
        });
    </script>
@endsection
