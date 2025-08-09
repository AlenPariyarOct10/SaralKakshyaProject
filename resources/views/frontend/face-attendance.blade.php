<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Face Recognition Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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

        .loading-spinner {
            border: 3px solid rgba(229, 231, 235, 1);
            border-top-color: rgba(16, 185, 129, 1);
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .face-detected {
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .recognition-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .recognition-status.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: rgba(16, 185, 129, 1);
        }

        .recognition-status.pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: rgba(245, 158, 11, 1);
        }

        .recognition-status.failed {
            background-color: rgba(239, 68, 68, 0.1);
            color: rgba(239, 68, 68, 1);
        }

        .location-indicator {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Face Recognition</h1>
                <p class="text-gray-600 text-sm">Automated attendance system</p>
            </div>

            <!-- Institute Selector -->
            <div class="relative w-64">
                <select id="instituteSelector" class="dropdown-select appearance-none block w-full px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 pr-10 text-sm" disabled>
                    <option value="" disabled selected>Detecting location...</option>
                </select>
                <div id="locationLoader" class="absolute right-8 top-1/2 transform -translate-y-1/2">
                    <div class="w-4 h-4 border-2 border-gray-300 border-t-emerald-500 rounded-full animate-spin"></div>
                </div>
            </div>
        </div>

        <!-- Location Status -->
        <div id="locationStatus" class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
            <div class="flex items-center gap-2 text-blue-800 text-sm">
                <i class="fas fa-map-marker-alt location-indicator"></i>
                <span id="locationText">Detecting your location...</span>
            </div>
        </div>

        <!-- Status Pills -->
        <div class="flex flex-wrap gap-2 mb-3">
            <div id="locationStatusPill" class="flex items-center gap-2 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                <div class="relative">
                    <i class="fas fa-map-marker-alt"></i>
                    <div id="locationStatusBadge" class="badge bg-yellow-400"></div>
                </div>
                <span>Location</span>
            </div>
            <div id="cameraStatus" class="flex items-center gap-2 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                <div class="relative">
                    <i class="fas fa-video"></i>
                    <div id="cameraStatusBadge" class="badge bg-yellow-400"></div>
                </div>
                <span>Camera</span>
            </div>
            <div id="apiStatus" class="flex items-center gap-2 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                <div class="relative">
                    <i class="fas fa-server"></i>
                    <div id="apiStatusBadge" class="badge bg-yellow-400"></div>
                </div>
                <span>API</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Camera Feed -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="relative">
                    <!-- Camera Header -->
                    <div class="bg-gray-800 text-white px-4 py-2 flex justify-between items-center">
                        <div class="flex items-center gap-2 text-sm">
                            <div id="recordingIndicator" class="w-2 h-2 rounded-full bg-red-500 hidden"></div>
                            <span id="cameraTitle">Camera Feed</span>
                        </div>
                        <div id="timeDisplay" class="text-gray-300 text-xs font-mono"></div>
                    </div>

                    <!-- Video Container -->
                    <div class="relative bg-gray-900 aspect-video">
                        <video id="video" class="w-full h-full object-cover" autoplay muted></video>
                        <canvas id="canvas" class="hidden absolute top-0 left-0 w-full h-full"></canvas>
                        <canvas id="overlayCanvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>

                        <!-- Face Detection Overlay -->
                        <div id="faceDetectionOverlay" class="absolute top-0 left-0 w-full h-full pointer-events-none"></div>

                        <!-- Scanning Effect -->
                        <div id="scanningEffect" class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-0 transition-opacity duration-300">
                            <div class="scanning-line"></div>
                        </div>

                        <!-- Status Overlay -->
                        <div id="statusOverlay" class="absolute bottom-3 left-3 right-3 bg-gray-900 bg-opacity-80 text-white px-3 py-2 rounded-lg opacity-0 transition-opacity duration-300 flex items-center gap-2 text-sm">
                            <div id="statusIcon" class="text-base">
                                <i class="fas fa-circle-notch fa-spin"></i>
                            </div>
                            <div>
                                <div id="statusTitle" class="font-medium">Initializing</div>
                                <div id="statusText" class="text-xs text-gray-300">Please wait...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Controls -->
                <div class="p-3 border-t border-gray-100">
                    <div class="flex flex-wrap gap-2 justify-between">
                        <button id="startBtn" class="flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-1.5 px-3 rounded-lg transition duration-200 text-sm" disabled>
                            <i class="fas fa-play"></i>
                            <span>Start Recognition</span>
                        </button>

                        <button id="stopBtn" class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-3 rounded-lg transition duration-200 text-sm hidden">
                            <i class="fas fa-stop"></i>
                            <span>Stop Recognition</span>
                        </button>

                        <button id="switchCameraBtn" class="flex items-center gap-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-1.5 px-3 rounded-lg transition duration-200 text-sm">
                            <i class="fas fa-sync-alt"></i>
                            <span>Switch Camera</span>
                        </button>

                        <button id="refreshLocationBtn" class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-lg transition duration-200 text-sm">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Refresh Location</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recognition Results Panel -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md overflow-hidden h-full flex flex-col">
                <div class="bg-gray-800 text-white px-4 py-2">
                    <h2 class="font-medium text-sm">Recognition Results</h2>
                </div>

                <div id="emptyState" class="flex-1 flex flex-col items-center justify-center p-4 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                        <i class="fas fa-user-circle text-3xl"></i>
                    </div>
                    <h3 class="text-base font-medium text-gray-800 mb-1">No Recognition Yet</h3>
                    <p class="text-gray-500 text-sm mb-4">Start recognition to identify attendees</p>
                </div>

                <div id="recognitionResult" class="flex-1 p-4 hidden">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 rounded-full bg-emerald-50 border-4 border-emerald-500 mx-auto mb-3 flex items-center justify-center overflow-hidden">
                            <img id="personImage" alt="Person" class="w-full h-full object-cover">
                            <i id="personIcon" class="fas fa-user-circle text-emerald-300 text-4xl"></i>
                        </div>
                        <h3 id="recognizedName" class="text-lg font-semibold text-gray-800"></h3>
                        <p id="recognizedId" class="text-gray-500 text-sm"></p>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">Recognition Status</div>
                            <div class="flex items-center justify-center">
                                <div id="recognitionStatus" class="recognition-status success">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span>Recognized</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500 mb-1">Date</div>
                                <div id="currentDate" class="font-medium text-sm"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500 mb-1">Time</div>
                                <div id="currentTime" class="font-medium text-sm"></div>
                            </div>
                        </div>

                        <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-200">
                            <div class="flex items-start gap-2">
                                <div class="text-emerald-500 mt-0.5">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm text-emerald-800">Attendance Recorded</div>
                                    <div class="text-xs text-emerald-600" id="attendanceTimestamp"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="loadingState" class="flex-1 flex flex-col items-center justify-center p-4 text-center hidden">
                    <div class="w-12 h-12 mb-3">
                        <div class="loading-spinner"></div>
                    </div>
                    <h3 class="text-base font-medium text-gray-800 mb-1">Processing</h3>
                    <p class="text-gray-500 text-sm">Analyzing facial features...</p>
                </div>

                <div id="errorState" class="flex-1 flex flex-col items-center justify-center p-4 text-center hidden">
                    <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-3">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-base font-medium text-gray-800 mb-1">Recognition Failed</h3>
                    <p id="errorMessage" class="text-gray-500 text-sm mb-4">Unable to detect a face in the frame</p>
                    <button id="retryBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-1.5 px-3 rounded-lg transition duration-200 text-sm">
                        Try Again
                    </button>
                </div>

                <!-- Bottom Actions -->
                <div class="p-3 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('welcome') }}" class="flex items-center justify-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-1.5 px-3 rounded-lg transition duration-200 text-center text-sm">
                            <i class="fas fa-arrow-left"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Constants
        const FLASK_API_URL = 'http://127.0.0.1:5000';

        // DOM Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const overlayCanvas = document.getElementById('overlayCanvas');
        const scanningEffect = document.getElementById('scanningEffect');
        const statusOverlay = document.getElementById('statusOverlay');
        const statusIcon = document.getElementById('statusIcon');
        const statusTitle = document.getElementById('statusTitle');
        const statusText = document.getElementById('statusText');
        const timeDisplay = document.getElementById('timeDisplay');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const refreshLocationBtn = document.getElementById('refreshLocationBtn');
        const emptyState = document.getElementById('emptyState');
        const recognitionResult = document.getElementById('recognitionResult');
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const errorMessage = document.getElementById('errorMessage');
        const retryBtn = document.getElementById('retryBtn');
        const recognizedName = document.getElementById('recognizedName');
        const recognizedId = document.getElementById('recognizedId');
        const recognitionStatus = document.getElementById('recognitionStatus');
        const currentDate = document.getElementById('currentDate');
        const currentTime = document.getElementById('currentTime');
        const attendanceTimestamp = document.getElementById('attendanceTimestamp');
        const recordingIndicator = document.getElementById('recordingIndicator');
        const cameraTitle = document.getElementById('cameraTitle');
        const personImage = document.getElementById('personImage');
        const personIcon = document.getElementById('personIcon');
        const faceDetectionOverlay = document.getElementById('faceDetectionOverlay');
        const instituteSelector = document.getElementById('instituteSelector');
        const locationLoader = document.getElementById('locationLoader');
        const locationStatus = document.getElementById('locationStatus');
        const locationText = document.getElementById('locationText');

        // Status indicators
        const locationStatusPill = document.getElementById('locationStatusPill');
        const locationStatusBadge = document.getElementById('locationStatusBadge');
        const cameraStatus = document.getElementById('cameraStatus');
        const cameraStatusBadge = document.getElementById('cameraStatusBadge');
        const apiStatus = document.getElementById('apiStatus');
        const apiStatusBadge = document.getElementById('apiStatusBadge');

        // Canvas contexts
        const context = canvas.getContext('2d');
        const overlayContext = overlayCanvas.getContext('2d');

        // State variables
        let stream = null;
        let isRecognizing = false;
        let recognitionTimer = null;
        let faceDetectionTimer = null;
        let facingMode = 'user';
        let instituteId = '';
        let hasFace = false;
        let faceBox = false;
        let isAPIConnected = false;
        let userLocation = null;
        let nearbyInstitutes = [];
        let isLocationDetected = false;
        let isCameraReady = false;

        // Location functions
        function updateLocationStatus(status) {
            if (status === 'detecting') {
                locationStatusPill.classList.remove('bg-gray-100', 'bg-green-100', 'bg-red-100');
                locationStatusPill.classList.add('bg-yellow-100');
                locationStatusBadge.classList.remove('bg-gray-400', 'bg-green-500', 'bg-red-500');
                locationStatusBadge.classList.add('bg-yellow-400');
            } else if (status === 'detected') {
                locationStatusPill.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100');
                locationStatusPill.classList.add('bg-green-100');
                locationStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-red-500');
                locationStatusBadge.classList.add('bg-green-500');
            } else if (status === 'error') {
                locationStatusPill.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-green-100');
                locationStatusPill.classList.add('bg-red-100');
                locationStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-green-500');
                locationStatusBadge.classList.add('bg-red-500');
            }
        }

        function getCurrentLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation is not supported by this browser'));
                    return;
                }

                updateLocationStatus('detecting');
                locationStatus.classList.remove('hidden');
                locationText.textContent = 'Detecting your location...';

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const location = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        };

                        userLocation = location;
                        isLocationDetected = true;
                        updateLocationStatus('detected');
                        locationText.textContent = `Location detected (±${Math.round(location.accuracy)}m accuracy)`;

                        resolve(location);
                    },
                    (error) => {
                        updateLocationStatus('error');
                        locationText.textContent = 'Failed to detect location. Please enable location services.';

                        let errorMessage = 'Location access denied';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location access denied by user';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information unavailable';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Location request timed out';
                                break;
                        }

                        reject(new Error(errorMessage));
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000
                    }
                );
            });
        }

        function loadNearbyInstitutes(location) {
            locationLoader.classList.remove('hidden');

            fetch('{{ route("attendance.nearby-institutes") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    latitude: location.latitude,
                    longitude: location.longitude
                })
            })
                .then(response => response.json())
                .then(data => {
                    locationLoader.classList.add('hidden');

                    if (data.success) {
                        nearbyInstitutes = data.institutes;
                        updateInstituteSelector(data.institutes);

                        if (data.institutes.length > 0) {
                            locationText.textContent = `Found ${data.institutes.length} nearby institute(s)`;
                            locationStatus.classList.remove('bg-blue-50', 'border-blue-200');
                            locationStatus.classList.add('bg-green-50', 'border-green-200');
                            locationText.classList.remove('text-blue-800');
                            locationText.classList.add('text-green-800');
                        } else {
                            locationText.textContent = 'No institutes found in your area';
                            locationStatus.classList.remove('bg-blue-50', 'border-blue-200');
                            locationStatus.classList.add('bg-yellow-50', 'border-yellow-200');
                            locationText.classList.remove('text-blue-800');
                            locationText.classList.add('text-yellow-800');
                        }
                    } else {
                        updateInstituteSelector([]);
                        locationText.textContent = 'Failed to load nearby institutes';
                        locationStatus.classList.remove('bg-blue-50', 'border-blue-200');
                        locationStatus.classList.add('bg-red-50', 'border-red-200');
                        locationText.classList.remove('text-blue-800');
                        locationText.classList.add('text-red-800');
                    }

                    // Check if we can enable start button
                    checkStartButtonState();
                })
                .catch(error => {
                    console.error('Error loading nearby institutes:', error);
                    locationLoader.classList.add('hidden');
                    updateInstituteSelector([]);
                    locationText.textContent = 'Error loading nearby institutes';
                    locationStatus.classList.remove('bg-blue-50', 'border-blue-200');
                    locationStatus.classList.add('bg-red-50', 'border-red-200');
                    locationText.classList.remove('text-blue-800');
                    locationText.classList.add('text-red-800');

                    checkStartButtonState();
                });
        }

        function updateInstituteSelector(institutes) {
            instituteSelector.innerHTML = '';

            if (institutes.length === 0) {
                instituteSelector.innerHTML = '<option value="" disabled selected>No institutes in your area</option>';
                instituteSelector.disabled = true;
            } else {
                instituteSelector.innerHTML = '<option value="" disabled selected>Select Institute</option>';

                institutes.forEach(institute => {
                    const option = document.createElement('option');
                    option.value = institute.id;
                    option.textContent = `${institute.name} (${institute.distance}m away)`;
                    instituteSelector.appendChild(option);
                });

                instituteSelector.disabled = false;
            }

            // Add event listener for institute selection
            instituteSelector.addEventListener('change', function() {
                checkStartButtonState();
            });
        }

        function checkStartButtonState() {
            // Enable start button only if camera is ready, location is detected, institute is selected, and API is connected
            const canStart = isCameraReady &&
                isLocationDetected &&
                instituteSelector.value &&
                isAPIConnected;

            startBtn.disabled = !canStart;
        }

        function initializeLocation() {
            getCurrentLocation()
                .then(location => {
                    console.log('Location detected:', location);
                    loadNearbyInstitutes(location);
                })
                .catch(error => {
                    console.error('Location error:', error);
                    // Fallback: show all institutes if location fails
                    locationLoader.classList.add('hidden');

                    const allInstitutes = {!! json_encode($institutes->map(function($institute) {
                return [
                    'id' => $institute->id,
                    'name' => $institute->name,
                    'distance' => 'Unknown',
                    'threshold' => $institute->threshold ?? 100
                ];
            })) !!};

                    updateInstituteSelector(allInstitutes);
                    isLocationDetected = true; // Allow fallback to work
                    checkStartButtonState();
                });
        }
        // Check API connection
        function checkAPIConnection() {
            fetch(`${FLASK_API_URL}/recognize-face`, {
                method: 'OPTIONS',
                mode: 'cors'
            })
                .then(response => {
                    if (response.ok || response.status === 204) {
                        isAPIConnected = true;
                        updateAPIStatus('connected');
                    } else {
                        isAPIConnected = false;
                        updateAPIStatus('error');
                    }
                    checkStartButtonState();
                })
                .catch(error => {
                    console.error('API connection error:', error);
                    isAPIConnected = false;
                    updateAPIStatus('error');
                    checkStartButtonState();
                });
        }

        // Update API status indicators
        function updateAPIStatus(status) {
            if (status === 'connecting') {
                apiStatus.classList.remove('bg-gray-100', 'bg-green-100', 'bg-red-100');
                apiStatus.classList.add('bg-yellow-100');
                apiStatusBadge.classList.remove('bg-gray-400', 'bg-green-500', 'bg-red-500');
                apiStatusBadge.classList.add('bg-yellow-400');
            } else if (status === 'connected') {
                apiStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100');
                apiStatus.classList.add('bg-green-100');
                apiStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-red-500');
                apiStatusBadge.classList.add('bg-green-500');
            } else if (status === 'error') {
                apiStatus.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-green-100');
                apiStatus.classList.add('bg-red-100');
                apiStatusBadge.classList.remove('bg-gray-400', 'bg-yellow-400', 'bg-green-500');
                apiStatusBadge.classList.add('bg-red-500');
            }
        }

        // Update time display
        function updateTimeDisplay() {
            const now = new Date();
            timeDisplay.textContent = now.toLocaleTimeString();

            if (currentDate.textContent === '') {
                currentDate.textContent = now.toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
            }

            if (currentTime.textContent === '') {
                currentTime.textContent = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
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
        function updateCameraStatus(camera) {
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
        }

        // Initialize camera
        async function initCamera() {
            try {
                updateCameraStatus('connecting');
                showStatus('Initializing', 'Requesting camera access...');

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: facingMode
                    },
                    audio: false
                });

                video.srcObject = stream;

                video.onloadedmetadata = () => {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    overlayCanvas.width = video.videoWidth;
                    overlayCanvas.height = video.videoHeight;

                    isCameraReady = true;
                    updateCameraStatus('active');
                    showStatus('Camera Ready', 'Camera initialized successfully', 'fa-check-circle', true);

                    startFaceDetection();
                    checkStartButtonState();
                };
            } catch (error) {
                console.error('Error accessing camera:', error);
                isCameraReady = false;
                updateCameraStatus('error');
                showStatus('Camera Error', 'Failed to access camera', 'fa-exclamation-triangle');
                checkStartButtonState();
            }
        }

        // Basic face detection (using Canvas API for demonstration)
        function detectFace() {
            if (!stream || video.paused || video.ended) {
                return;
            }

            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            overlayContext.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

            // Simulate face detection - in real implementation, use proper face detection library
            hasFace = true;
            faceBox = true;

            if (hasFace && faceBox && isRecognizing) {
                const boxWidth = canvas.width * 0.4;
                const boxHeight = canvas.height * 0.6;
                const boxX = (canvas.width - boxWidth) / 2;
                const boxY = (canvas.height - boxHeight) / 2;

                overlayContext.strokeStyle = '#10B981';
                overlayContext.lineWidth = 3;
                overlayContext.setLineDash([]);
                overlayContext.strokeRect(boxX, boxY, boxWidth, boxHeight);

                overlayContext.strokeStyle = 'rgba(16, 185, 129, 0.3)';
                overlayContext.lineWidth = 5;
                overlayContext.strokeRect(boxX - 3, boxY - 3, boxWidth + 6, boxHeight + 6);
            }

            faceDetectionOverlay.innerHTML = '';
        }

        // Start face detection
        function startFaceDetection() {
            if (faceDetectionTimer) {
                clearInterval(faceDetectionTimer);
            }
            faceDetectionTimer = setInterval(detectFace, 200);
        }

        // Start face recognition
        function startRecognition() {
            if (!stream) {
                showStatus('Error', 'Camera not initialized', 'fa-exclamation-triangle');
                return;
            }

            if (!isAPIConnected) {
                showStatus('Error', 'API not connected', 'fa-exclamation-triangle', true);
                return;
            }

            if (!instituteSelector.value) {
                showStatus('Error', 'Please select an institute', 'fa-exclamation-triangle', true);
                return;
            }

            instituteId = instituteSelector.value;
            isRecognizing = true;
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            recordingIndicator.classList.remove('hidden');
            cameraTitle.textContent = 'Recognition in Progress';

            scanningEffect.classList.remove('opacity-0');
            scanningEffect.classList.add('opacity-100');

            showStatus('Recognition Active', 'Looking for faces...', 'fa-search');

            emptyState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            recognitionResult.classList.add('hidden');
            errorState.classList.add('hidden');

            checkForFaceAndRecognize();
        }

        // Check for face and recognize when found
        function checkForFaceAndRecognize() {
            if (recognitionTimer) {
                clearInterval(recognitionTimer);
            }

            recognitionTimer = setInterval(() => {
                if (hasFace && faceBox) {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = canvas.toDataURL('image/jpeg');
                    recognizeFace(imageData);
                    clearInterval(recognitionTimer);
                } else {
                    detectFace();
                }
            }, 500);
        }

        // Stop face recognition
        function stopRecognition() {
            isRecognizing = false;
            if (recognitionTimer) {
                clearInterval(recognitionTimer);
            }

            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            recordingIndicator.classList.add('hidden');
            cameraTitle.textContent = 'Camera Feed';

            scanningEffect.classList.remove('opacity-100');
            scanningEffect.classList.add('opacity-0');

            hideStatus();
        }

        // API call for face recognition
        function recognizeFace(imageData) {
            showStatus('Processing', 'Analyzing facial features...', 'fa-cog fa-spin');

            fetch(`${FLASK_API_URL}/recognize-face`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    image: imageData,
                    institute_id: instituteId,
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchStudentDetails(data.student_id, data.confidence);
                    } else {
                        handleRecognitionFailure(data.message || 'No matching face found');
                    }
                })
                .catch(error => {
                    handleRecognitionFailure('No matching face found');
                });
        }

        // Fetch student details from the backend
        async function fetchStudentDetails(studentId, confidence) {
            try {
                const response = await fetch("{{route('student.getInfo')}}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        id: studentId,
                        institute_id: instituteId,
                    })
                });

                const data = await response.json();

                if (data.success) {
                    handleRecognitionSuccess({
                        success: true,
                        person: data.student,
                        confidence: confidence
                    });
                } else {
                    handleRecognitionFailure(data.message || 'Student information not found');
                }
            } catch (error) {
                console.error('Error fetching student details:', error);
                handleRecognitionFailure('Failed to fetch student information');
            }
        }

        // Handle successful recognition
        function handleRecognitionSuccess(response) {
            showStatus('Success', 'Face recognized successfully!', 'fa-check-circle', true);

            loadingState.classList.add('hidden');
            recognitionResult.classList.remove('hidden');
            errorState.classList.add('hidden');

            recognizedName.textContent = response.person.fname+" "+response.person.lname;
            recognizedId.textContent = `Student • Roll : ${response.person.id} • ${response.person.institute.name}`;

            recognitionStatus.className = 'recognition-status success';
            recognitionStatus.innerHTML = '<i class="fas fa-check-circle mr-1"></i><span>Recognized</span>';

            if(response.person.profile_picture != null && response.person.profile_picture.trim() !== '') {
                personImage.src = "{{ asset('storage/') }}/" + response.person.profile_picture;
                personImage.classList.remove("hidden");
                personIcon.classList.add("hidden");
            } else {
                personImage.classList.add("hidden");
                personIcon.classList.remove("hidden");
            }

            const now = new Date();
            attendanceTimestamp.textContent = now.toLocaleString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            logAttendance(response.person.id);
            stopRecognition();
        }

        // Handle failed recognition
        function handleRecognitionFailure(reason) {
            loadingState.classList.add('hidden');
            recognitionResult.classList.add('hidden');
            errorState.classList.remove('hidden');

            errorMessage.textContent = reason;
            stopRecognition();
        }

        // API call for logging attendance
        function logAttendance(studentId) {
            const attendanceData = {
                student_id: studentId,
                institute_id: instituteId,
                timestamp: new Date().toISOString(),
            };

            fetch('{{ route("student.face.mark") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(attendanceData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Attendance logged:', data);
                    showAttendanceNotification(studentId);
                })
                .catch(error => {
                    console.error('Error logging attendance:', error);
                });
        }

        // Show attendance notification
        function showAttendanceNotification(studentId) {
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 slide-in';
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div>
                        <div class="font-medium">Attendance Recorded</div>
                        <div class="text-xs">Student ID: ${studentId}</div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }

        // Switch camera (front/back)
        function switchCamera() {
            facingMode = facingMode === 'user' ? 'environment' : 'user';
            initCamera();
        }

        // Event listeners
        startBtn.addEventListener('click', startRecognition);
        stopBtn.addEventListener('click', stopRecognition);
        switchCameraBtn.addEventListener('click', switchCamera);
        refreshLocationBtn.addEventListener('click', initializeLocation);
        retryBtn.addEventListener('click', startRecognition);

        // Initialize everything
        initCamera();
        checkAPIConnection();
        initializeLocation();

        // Periodically check API connection
        setInterval(checkAPIConnection, 30000);
    });
</script>
</body>
</html>
