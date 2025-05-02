<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/js/all.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .pulse {
            animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .face-box {
            border: 3px solid #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .scanning-line {
            height: 2px;
            background: linear-gradient(90deg, rgba(16, 185, 129, 0) 0%, rgba(16, 185, 129, 1) 50%, rgba(16, 185, 129, 0) 100%);
            animation: scan 2s linear infinite;
            position: absolute;
            left: 0;
            right: 0;
        }

        @keyframes scan {
            0% {
                top: 0;
            }
            100% {
                top: 100%;
            }
        }

        .dropdown-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        .badge {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .slide-in {
            animation: slideIn 0.3s ease forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Face Recognition</h1>
                <p class="text-gray-600 mt-1">Automated attendance system</p>
            </div>

            <!-- Institute Selector -->
            <div class="relative w-64">
                <select id="instituteSelector" class="dropdown-select appearance-none block w-full px-4 py-3 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 pr-10">
                    <option value="" disabled selected>Select Institute</option>
                    <option value="1">Main Campus</option>
                    <option value="2">Engineering Department</option>
                    <option value="3">Business School</option>
                    <option value="4">Medical College</option>
                    <option value="5">Arts & Sciences</option>
                </select>
            </div>
        </div>

        <!-- Status Pills -->
        <div class="flex flex-wrap gap-3 mb-4">
            <div id="cameraStatus" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                <div class="relative">
                    <i class="fas fa-video"></i>
                    <div id="cameraStatusBadge" class="badge bg-yellow-400"></div>
                </div>
                <span>Camera</span>
            </div>

            <div id="faceStatus" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                <div class="relative">
                    <i class="fas fa-user"></i>
                    <div id="faceStatusBadge" class="badge bg-gray-400"></div>
                </div>
                <span>Face Detection</span>
            </div>

            <div id="recognitionStatus" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                <div class="relative">
                    <i class="fas fa-fingerprint"></i>
                    <div id="recognitionStatusBadge" class="badge bg-gray-400"></div>
                </div>
                <span>Recognition</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Camera Feed -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="relative">
                    <!-- Camera Header -->
                    <div class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div id="recordingIndicator" class="w-3 h-3 rounded-full bg-red-500 hidden"></div>
                            <span id="cameraTitle">Camera Feed</span>
                        </div>
                        <div id="timeDisplay" class="text-gray-300 text-sm font-mono"></div>
                    </div>

                    <!-- Video Container -->
                    <div class="relative bg-gray-900 aspect-video">
                        <video id="video" class="w-full h-full object-cover" autoplay muted></video>
                        <canvas id="canvas" class="hidden absolute top-0 left-0 w-full h-full"></canvas>
                        <canvas id="overlayCanvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>

                        <!-- Face Detection Overlay (will be controlled by JS) -->
                        <div id="faceDetectionOverlay" class="absolute top-0 left-0 w-full h-full pointer-events-none"></div>

                        <!-- Scanning Effect (visible during recognition) -->
                        <div id="scanningEffect" class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-0 transition-opacity duration-300">
                            <div class="scanning-line"></div>
                        </div>

                        <!-- Status Overlay -->
                        <div id="statusOverlay" class="absolute bottom-4 left-4 right-4 bg-gray-900 bg-opacity-80 text-white px-4 py-3 rounded-lg opacity-0 transition-opacity duration-300 flex items-center gap-3">
                            <div id="statusIcon" class="text-xl">
                                <i class="fas fa-circle-notch fa-spin"></i>
                            </div>
                            <div>
                                <div id="statusTitle" class="font-medium">Initializing</div>
                                <div id="statusText" class="text-sm text-gray-300">Please wait...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Controls -->
                <div class="p-5 border-t border-gray-100">
                    <div class="flex flex-wrap gap-3 justify-between">
                        <button id="startBtn" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 px-5 rounded-lg transition duration-200">
                            <i class="fas fa-play"></i>
                            <span>Start Recognition</span>
                        </button>

                        <button id="stopBtn" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-lg transition duration-200 hidden">
                            <i class="fas fa-stop"></i>
                            <span>Stop Recognition</span>
                        </button>

                        <div class="flex gap-3">
                            <button id="captureBtn" class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition duration-200">
                                <i class="fas fa-camera"></i>
                                <span>Capture</span>
                            </button>

                            <button id="switchCameraBtn" class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition duration-200">
                                <i class="fas fa-sync-alt"></i>
                                <span>Switch Camera</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recognition Results Panel -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-full flex flex-col">
                <div class="bg-gray-800 text-white px-6 py-4">
                    <h2 class="font-semibold">Recognition Results</h2>
                </div>

                <div id="emptyState" class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
                        <i class="fas fa-user-circle text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Recognition Yet</h3>
                    <p class="text-gray-500 mb-6">Start the recognition process to identify attendees</p>
                </div>

                <div id="recognitionResult" class="flex-1 p-6 hidden">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-emerald-50 border-4 border-emerald-500 mx-auto mb-4 flex items-center justify-center overflow-hidden">
                            <img id="personImage" src="/placeholder.svg" alt="Person" class="w-full h-full object-cover hidden">
                            <i id="personIcon" class="fas fa-user-circle text-emerald-300 text-5xl"></i>
                        </div>
                        <h3 id="recognizedName" class="text-xl font-semibold text-gray-800"></h3>
                        <p id="recognizedId" class="text-gray-500"></p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500 mb-1">Recognition Confidence</div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                    <div id="confidenceBar" class="bg-emerald-500 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                                <div id="confidenceValue" class="text-sm font-medium">0%</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-500 mb-1">Date</div>
                                <div id="currentDate" class="font-medium"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-500 mb-1">Time</div>
                                <div id="currentTime" class="font-medium"></div>
                            </div>
                        </div>

                        <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                            <div class="flex items-start gap-3">
                                <div class="text-emerald-500 mt-0.5">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-emerald-800">Attendance Recorded</div>
                                    <div class="text-sm text-emerald-600" id="attendanceTimestamp"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="loadingState" class="flex-1 flex flex-col items-center justify-center p-8 text-center hidden">
                    <div class="w-16 h-16 mb-4">
                        <div class="w-full h-full rounded-full border-4 border-gray-200 border-t-emerald-500 animate-spin"></div>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Processing</h3>
                    <p class="text-gray-500">Analyzing facial features...</p>
                </div>

                <div id="errorState" class="flex-1 flex flex-col items-center justify-center p-8 text-center hidden">
                    <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-4">
                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Recognition Failed</h3>
                    <p id="errorMessage" class="text-gray-500 mb-6">Unable to detect a face in the frame</p>
                    <button id="retryBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                        Try Again
                    </button>
                </div>

                <!-- Bottom Actions -->
                <div class="p-5 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('welcome') }}" class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-arrow-left"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('welcome') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-edit"></i>
                            <span>Manual</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const overlayCanvas = document.getElementById('overlayCanvas');
        const faceDetectionOverlay = document.getElementById('faceDetectionOverlay');
        const scanningEffect = document.getElementById('scanningEffect');
        const statusOverlay = document.getElementById('statusOverlay');
        const statusIcon = document.getElementById('statusIcon');
        const statusTitle = document.getElementById('statusTitle');
        const statusText = document.getElementById('statusText');
        const timeDisplay = document.getElementById('timeDisplay');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const captureBtn = document.getElementById('captureBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const emptyState = document.getElementById('emptyState');
        const recognitionResult = document.getElementById('recognitionResult');
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const errorMessage = document.getElementById('errorMessage');
        const retryBtn = document.getElementById('retryBtn');
        const recognizedName = document.getElementById('recognizedName');
        const recognizedId = document.getElementById('recognizedId');
        const confidenceBar = document.getElementById('confidenceBar');
        const confidenceValue = document.getElementById('confidenceValue');
        const currentDate = document.getElementById('currentDate');
        const currentTime = document.getElementById('currentTime');
        const attendanceTimestamp = document.getElementById('attendanceTimestamp');
        const recordingIndicator = document.getElementById('recordingIndicator');
        const cameraTitle = document.getElementById('cameraTitle');
        const personImage = document.getElementById('personImage');
        const personIcon = document.getElementById('personIcon');

        // Status indicators
        const cameraStatus = document.getElementById('cameraStatus');
        const faceStatus = document.getElementById('faceStatus');
        const recognitionStatus = document.getElementById('recognitionStatus');
        const cameraStatusBadge = document.getElementById('cameraStatusBadge');
        const faceStatusBadge = document.getElementById('faceStatusBadge');
        const recognitionStatusBadge = document.getElementById('recognitionStatusBadge');

        // Canvas contexts
        const context = canvas.getContext('2d');
        const overlayContext = overlayCanvas.getContext('2d');

        // State variables
        let stream = null;
        let isRecognizing = false;
        let recognitionInterval = null;
        let facingMode = 'user'; // 'user' for front camera, 'environment' for back camera
        let detectedFaces = [];
        let instituteId = '';

        // Update time display
        function updateTimeDisplay() {
            const now = new Date();
            timeDisplay.textContent = now.toLocaleTimeString();

            if (currentDate.textContent === '') {
                currentDate.textContent = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            if (currentTime.textContent === '') {
                currentTime.textContent = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }

        // Initialize time display and update every second
        updateTimeDisplay();
        setInterval(updateTimeDisplay, 1000);

        // Show status overlay with message
        function showStatus(title, message, icon = 'fa-circle-notch fa-spin', autoHide = false) {
            statusTitle.textContent = title;
            statusText.textContent = message;
            statusIcon.innerHTML = `<i class="fas ${icon}"></i>`;
            statusOverlay.classList.remove('opacity-0');
            statusOverlay.classList.add('opacity-100');

            if (autoHide) {
                setTimeout(() => {
                    statusOverlay.classList.remove('opacity-100');
                    statusOverlay.classList.add('opacity-0');
                }, 3000);
            }
        }

        // Hide status overlay
        function hideStatus() {
            statusOverlay.classList.remove('opacity-100');
            statusOverlay.classList.add('opacity-0');
        }

        // Update status indicators
        function updateStatusIndicators(camera, face, recognition) {
            // Camera status
            if (camera === 'connecting') {
                cameraStatus.classList.remove('bg-gray-100', 'bg-green-100', 'bg-red-100');
                cameraStatus.classList.add('bg-yellow-100');
                cameraStatusBadge.classList.remove('bg-gray-400', 'bg-green-500', 'bg-red-500');
                cameraStatusBadge.classList.add('bg-yellow-400');
            } else if (camera === 'active') {
                cameraStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100');
                cameraStatus.classList.add('bg-green-100');
                cameraStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-red-500');
                cameraStatusBadge.classList.add('bg-green-500');
            } else if (camera === 'error') {
                cameraStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-green-100');
                cameraStatus.classList.add('bg-red-100');
                cameraStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-green-500');
                cameraStatusBadge.classList.add('bg-red-500');
            }

            // Face detection status
            if (face === 'inactive') {
                faceStatus.classList.remove('bg-green-100', 'bg-yellow-100', 'bg-red-100');
                faceStatus.classList.add('bg-gray-100');
                faceStatusBadge.classList.remove('bg-green-500', 'bg-yellow-400', 'bg-red-500');
                faceStatusBadge.classList.add('bg-gray-400');
            } else if (face === 'detecting') {
                faceStatus.classList.remove('bg-gray-100', 'bg-green-100', 'bg-red-100');
                faceStatus.classList.add('bg-yellow-100');
                faceStatusBadge.classList.remove('bg-gray-400', 'bg-green-500', 'bg-red-500');
                faceStatusBadge.classList.add('bg-yellow-400');
            } else if (face === 'detected') {
                faceStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100');
                faceStatus.classList.add('bg-green-100');
                faceStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-red-500');
                faceStatusBadge.classList.add('bg-green-500');
            } else if (face === 'error') {
                faceStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-green-100');
                faceStatus.classList.add('bg-red-100');
                faceStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-green-500');
                faceStatusBadge.classList.add('bg-red-500');
            }

            // Recognition status
            if (recognition === 'inactive') {
                recognitionStatus.classList.remove('bg-green-100', 'bg-yellow-100', 'bg-red-100');
                recognitionStatus.classList.add('bg-gray-100');
                recognitionStatusBadge.classList.remove('bg-green-500', 'bg-yellow-400', 'bg-red-500');
                recognitionStatusBadge.classList.add('bg-gray-400');
            } else if (recognition === 'processing') {
                recognitionStatus.classList.remove('bg-gray-100', 'bg-green-100', 'bg-red-100');
                recognitionStatus.classList.add('bg-yellow-100');
                recognitionStatusBadge.classList.remove('bg-gray-400', 'bg-green-500', 'bg-red-500');
                recognitionStatusBadge.classList.add('bg-yellow-400');
            } else if (recognition === 'success') {
                recognitionStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100');
                recognitionStatus.classList.add('bg-green-100');
                recognitionStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-red-500');
                recognitionStatusBadge.classList.add('bg-green-500');
            } else if (recognition === 'error') {
                recognitionStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-green-100');
                recognitionStatus.classList.add('bg-red-100');
                recognitionStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-green-500');
                recognitionStatusBadge.classList.add('bg-red-500');
            }
        }

        // Initialize camera
        async function initCamera() {
            try {
                updateStatusIndicators('connecting', 'inactive', 'inactive');
                showStatus('Initializing', 'Requesting camera access...');

                // Stop any existing stream
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // Request camera access
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: facingMode
                    },
                    audio: false
                });

                video.srcObject = stream;

                // Wait for video to be ready
                video.onloadedmetadata = () => {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    overlayCanvas.width = video.videoWidth;
                    overlayCanvas.height = video.videoHeight;

                    updateStatusIndicators('active', 'inactive', 'inactive');
                    showStatus('Camera Ready', 'Camera initialized successfully', 'fa-check-circle', true);

                    // Start face detection
                    startFaceDetection();
                };
            } catch (error) {
                console.error('Error accessing camera:', error);
                updateStatusIndicators('error', 'inactive', 'inactive');
                showStatus('Camera Error', 'Failed to access camera', 'fa-exclamation-triangle');
            }
        }

        // Start face detection
        function startFaceDetection() {
            updateStatusIndicators('active', 'detecting', 'inactive');

            // In a real application, you would use a face detection library
            // For this demo, we'll simulate face detection
            simulateFaceDetection();
        }

        // Simulate face detection (in a real app, use a library like face-api.js)
        function simulateFaceDetection() {
            // Clear previous detection interval if any
            if (window.faceDetectionInterval) {
                clearInterval(window.faceDetectionInterval);
            }

            window.faceDetectionInterval = setInterval(() => {
                if (!video.paused && !video.ended) {
                    // Capture current frame for processing
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Simulate face detection (random position and size)
                    const shouldDetectFace = Math.random() > 0.3; // 70% chance to detect a face

                    if (shouldDetectFace) {
                        // Generate a random face position (in a real app, this would come from the face detection API)
                        const centerX = canvas.width * (0.4 + Math.random() * 0.2); // Center-ish with some variation
                        const centerY = canvas.height * (0.4 + Math.random() * 0.2);
                        const faceWidth = canvas.width * (0.2 + Math.random() * 0.1);
                        const faceHeight = faceWidth * 1.3; // Faces are usually taller than wide

                        detectedFaces = [{
                            x: centerX - faceWidth / 2,
                            y: centerY - faceHeight / 2,
                            width: faceWidth,
                            height: faceHeight,
                            confidence: 0.7 + Math.random() * 0.3 // Random confidence between 0.7 and 1.0
                        }];

                        updateStatusIndicators('active', 'detected', isRecognizing ? 'processing' : 'inactive');
                        drawFaceOverlays();
                    } else {
                        detectedFaces = [];
                        updateStatusIndicators('active', 'detecting', isRecognizing ? 'processing' : 'inactive');
                        clearFaceOverlays();
                    }
                }
            }, 500); // Check for faces every 500ms
        }

        // Draw face detection overlays
        function drawFaceOverlays() {
            // Clear previous overlays
            overlayContext.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

            // Draw face boxes for each detected face
            detectedFaces.forEach(face => {
                // Draw face box
                overlayContext.strokeStyle = '#10B981'; // Emerald green
                overlayContext.lineWidth = 3;
                overlayContext.beginPath();
                overlayContext.rect(face.x, face.y, face.width, face.height);
                overlayContext.stroke();

                // Draw confidence label
                const confidencePercent = Math.round(face.confidence * 100);
                overlayContext.fillStyle = 'rgba(16, 185, 129, 0.8)';
                overlayContext.fillRect(face.x, face.y - 25, 120, 20);
                overlayContext.fillStyle = 'white';
                overlayContext.font = '12px Inter, sans-serif';
                overlayContext.fillText(`Confidence: ${confidencePercent}%`, face.x + 5, face.y - 10);

                // Draw corner accents
                const cornerLength = 20;
                overlayContext.strokeStyle = '#10B981';
                overlayContext.lineWidth = 4;

                // Top-left corner
                overlayContext.beginPath();
                overlayContext.moveTo(face.x, face.y + cornerLength);
                overlayContext.lineTo(face.x, face.y);
                overlayContext.lineTo(face.x + cornerLength, face.y);
                overlayContext.stroke();

                // Top-right corner
                overlayContext.beginPath();
                overlayContext.moveTo(face.x + face.width - cornerLength, face.y);
                overlayContext.lineTo(face.x + face.width, face.y);
                overlayContext.lineTo(face.x + face.width, face.y + cornerLength);
                overlayContext.stroke();

                // Bottom-right corner
                overlayContext.beginPath();
                overlayContext.moveTo(face.x + face.width, face.y + face.height - cornerLength);
                overlayContext.lineTo(face.x + face.width, face.y + face.height);
                overlayContext.lineTo(face.x + face.width - cornerLength, face.y + face.height);
                overlayContext.stroke();

                // Bottom-left corner
                overlayContext.beginPath();
                overlayContext.moveTo(face.x + cornerLength, face.y + face.height);
                overlayContext.lineTo(face.x, face.y + face.height);
                overlayContext.lineTo(face.x, face.y + face.height - cornerLength);
                overlayContext.stroke();
            });
        }

        // Clear face overlays
        function clearFaceOverlays() {
            overlayContext.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
        }

        // Start face recognition
        function startRecognition() {
            if (!stream) {
                showStatus('Error', 'Camera not initialized', 'fa-exclamation-triangle');
                return;
            }

            // Check if institute is selected
            if (!document.getElementById('instituteSelector').value) {
                showStatus('Error', 'Please select an institute', 'fa-exclamation-triangle', true);
                return;
            }

            instituteId = document.getElementById('instituteSelector').value;
            isRecognizing = true;
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            recordingIndicator.classList.remove('hidden');
            cameraTitle.textContent = 'Recognition in Progress';

            // Show scanning effect
            scanningEffect.classList.remove('opacity-0');
            scanningEffect.classList.add('opacity-100');

            updateStatusIndicators('active', detectedFaces.length > 0 ? 'detected' : 'detecting', 'processing');
            showStatus('Recognition Active', 'Looking for faces...', 'fa-search');

            // Hide empty state, show loading state
            emptyState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            recognitionResult.classList.add('hidden');
            errorState.classList.add('hidden');

            // Simulate recognition process
            recognitionInterval = setInterval(() => {
                if (detectedFaces.length > 0) {
                    // Capture current frame
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Get image data for processing
                    const imageData = canvas.toDataURL('image/jpeg');

                    // Call API for face recognition (placeholder)
                    recognizeFace(imageData);

                    // Stop the interval after starting recognition
                    clearInterval(recognitionInterval);
                }
            }, 1000);
        }

        // Stop face recognition
        function stopRecognition() {
            isRecognizing = false;
            clearInterval(recognitionInterval);
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            recordingIndicator.classList.add('hidden');
            cameraTitle.textContent = 'Camera Feed';

            // Hide scanning effect
            scanningEffect.classList.remove('opacity-100');
            scanningEffect.classList.add('opacity-0');

            updateStatusIndicators('active', detectedFaces.length > 0 ? 'detected' : 'detecting', 'inactive');
            hideStatus();
        }

        // API call placeholder for face recognition
        function recognizeFace(imageData) {
            showStatus('Processing', 'Analyzing facial features...', 'fa-cog fa-spin');

            // Simulate API call with delay
            setTimeout(() => {
                // Simulate successful recognition (80% of the time)
                if (Math.random() > 0.2) {
                    const confidence = 0.75 + Math.random() * 0.25; // Random confidence between 0.75 and 1.0
                    const mockResponse = {
                        success: true,
                        person: {
                            id: Math.floor(Math.random() * 1000),
                            name: 'John Doe',
                            employeeId: 'EMP' + Math.floor(Math.random() * 10000),
                            department: 'Computer Science',
                            role: 'Student'
                        },
                        confidence: confidence
                    };

                    handleRecognitionSuccess(mockResponse);
                } else {
                    // Simulate failed recognition
                    handleRecognitionFailure('Unable to match face with any registered user');
                }
            }, 3000);
        }

        // Handle successful recognition
        function handleRecognitionSuccess(response) {
            updateStatusIndicators('active', 'detected', 'success');
            showStatus('Success', 'Face recognized successfully!', 'fa-check-circle', true);

            // Hide loading state, show recognition result
            loadingState.classList.add('hidden');
            recognitionResult.classList.remove('hidden');
            errorState.classList.add('hidden');

            // Update recognition result
            recognizedName.textContent = response.person.name;
            recognizedId.textContent = `${response.person.role} • ${response.person.employeeId}`;

            // Update confidence bar
            const confidencePercent = Math.round(response.confidence * 100);
            confidenceBar.style.width = `${confidencePercent}%`;
            confidenceValue.textContent = `${confidencePercent}%`;

            // Update attendance timestamp
            const now = new Date();
            attendanceTimestamp.textContent = now.toLocaleString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Log attendance (API placeholder)
            logAttendance(response.person.id);

            // Stop recognition after successful match
            stopRecognition();
        }

        // Handle failed recognition
        function handleRecognitionFailure(reason) {
            updateStatusIndicators('active', detectedFaces.length > 0 ? 'detected' : 'detecting', 'error');

            // Hide loading state, show error state
            loadingState.classList.add('hidden');
            recognitionResult.classList.add('hidden');
            errorState.classList.remove('hidden');

            // Update error message
            errorMessage.textContent = reason;

            // Stop recognition
            stopRecognition();
        }

        // API call placeholder for logging attendance
        function logAttendance(personId) {
            console.log(`Logging attendance for person ID: ${personId} at institute ID: ${instituteId}`);

            // Example API call structure (commented out)
            /*
            fetch('/api/attendance/log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    personId: personId,
                    instituteId: instituteId,
                    timestamp: new Date().toISOString(),
                    method: 'face_recognition'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Attendance logged:', data);
            })
            .catch(error => {
                console.error('Error logging attendance:', error);
            });
            */
        }

        // Capture current frame
        function captureFrame() {
            if (!stream) {
                showStatus('Error', 'Camera not initialized', 'fa-exclamation-triangle', true);
                return;
            }

            // Draw current frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // If faces are detected, draw overlays
            if (detectedFaces.length > 0) {
                detectedFaces.forEach(face => {
                    context.strokeStyle = '#10B981';
                    context.lineWidth = 3;
                    context.strokeRect(face.x, face.y, face.width, face.height);
                });
            }

            // Convert to image data URL
            const imageData = canvas.toDataURL('image/jpeg');

            // In a real app, you might want to save this image or send it to the server
            showStatus('Captured', 'Frame captured successfully', 'fa-camera', true);

            // For demo purposes, we'll just log it
            console.log('Frame captured');
        }

        // Switch camera (front/back)
        function switchCamera() {
            facingMode = facingMode === 'user' ? 'environment' : 'user';
            initCamera();
        }

        // Event listeners
        startBtn.addEventListener('click', startRecognition);
        stopBtn.addEventListener('click', stopRecognition);
        captureBtn.addEventListener('click', captureFrame);
        switchCameraBtn.addEventListener('click', switchCamera);
        retryBtn.addEventListener('click', () => {
            errorState.classList.add('hidden');
            emptyState.classList.remove('hidden');
        });

        // Initialize camera on page load
        initCamera();
    });
</script>
</body>
</html>
