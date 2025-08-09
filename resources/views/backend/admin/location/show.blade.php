@extends('backend.layout.admin-dashboard-layout')

@section('content')
    <main class="p-6 bg-gray-50 dark:bg-gray-900 scrollable-content p-4 md:p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Attendance Location Setup</h2>
                    <p class="text-gray-600 dark:text-gray-400">Configure attendance geolocation settings for your institute</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <button id="saveLocationBtn" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Location
                    </button>
                </div>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">How to Set Up Attendance Location</h3>
                <div class="prose dark:prose-invert max-w-none">
                    <ol class="list-decimal pl-5 space-y-2">
                        <li>Set coordinates either by:
                            <ul class="list-disc pl-5 mt-2">
                                <li>Manually entering latitude and longitude values</li>
                                <li>Clicking on the map to select a location</li>
                            </ul>
                        </li>
                        <li>Set a threshold radius (in meters) to define the acceptable check-in area</li>
                        <li>Click the "Save Location" button to update your institute's attendance location</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Location Card -->
        <div class="location-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Campus Location</h3>
                    <div class="mt-2 sm:mt-0">
                        <button id="resetLocationBtn" class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Reset Location
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Form Fields -->
                    <div class="space-y-4">
                        <form id="locationForm">
                            @csrf
                            <input type="hidden" name="institute_id" value="{{ session()->get('institute_id') }}">

                            <!-- Coordinates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                                    <input type="number" name="latitude" id="latitude" step="0.000001" placeholder="e.g., 37.7749"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           value="{{ $institute->latitude ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                                    <input type="number" name="longitude" id="longitude" step="0.000001" placeholder="e.g., -122.4194"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           value="{{ $institute->longitude ?? '' }}">
                                </div>
                            </div>

                            <!-- Threshold -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Threshold Radius (meters)</label>
                                <input type="number" name="threshold" id="threshold" min="10" max="1000"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                       value="{{ $institute->threshold ?? 100 }}">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Students must be within this distance to mark attendance</p>
                            </div>
                        </form>
                    </div>

                    <!-- Right Column: Map -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Location on Map</label>
                        <div class="h-80 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
                            <div class="map-container h-full w-full" id="map"></div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click on the map to set coordinates</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Location Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Location Status</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" id="location-status">
                                @if(isset($institute->latitude) && isset($institute->longitude))
                                    <span class="text-green-600 dark:text-green-400">Configured</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">Not Configured</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Threshold Radius</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" id="threshold-display">
                                {{ $institute->threshold ?? 100 }}m
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Updated</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" id="last-updated">
                                @if(isset($institute->updated_at))
                                    {{ \Carbon\Carbon::parse($institute->updated_at)->format('M d, Y h:i A') }}
                                @else
                                    Never
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Global variables
        let map;
        let marker;
        let thresholdCircle;

        // Initialize the map when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            setupEventListeners();
        });

        // Initialize map
        function initMap() {
            // Get initial coordinates from form or use defaults
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const thresholdInput = document.getElementById('threshold');

            let initialThreshold = parseInt(thresholdInput.value) || 100;

            // Default fallback coordinates (San Francisco)
            const fallbackLat = 27.702403;
            const fallbackLng = 85.31988;

            // Function to initialize map with given coordinates
            function createMap(lat, lng) {
                // Create map
                map = L.map('map').setView([lat, lng], 13);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Update input fields with current position
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);

                // Add a marker at the position
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                // Add circle to represent threshold
                thresholdCircle = L.circle([lat, lng], {
                    color: 'blue',
                    fillColor: '#30f',
                    fillOpacity: 0.2,
                    radius: initialThreshold
                }).addTo(map);

                // Update input fields when marker is moved
                marker.on('dragend', function(event) {
                    const position = marker.getLatLng();
                    latInput.value = position.lat.toFixed(6);
                    lngInput.value = position.lng.toFixed(6);
                    thresholdCircle.setLatLng(position);
                });

                // Update marker position when clicking on map
                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    latInput.value = e.latlng.lat.toFixed(6);
                    lngInput.value = e.latlng.lng.toFixed(6);
                    thresholdCircle.setLatLng(e.latlng);
                });

                // Fix map display issues by triggering a resize after initialization
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            }

            // Check if coordinates are already provided in form inputs
            if (latInput.value && lngInput.value) {
                const inputLat = parseFloat(latInput.value);
                const inputLng = parseFloat(lngInput.value);
                createMap(inputLat, inputLng);
                return;
            }

            // Try to get user's current location
            if (navigator.geolocation) {
                // Show loading message or spinner here if desired
                console.log('Getting your location...');

                navigator.geolocation.getCurrentPosition(
                    // Success callback
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        console.log('Location found:', userLat, userLng);
                        createMap(userLat, userLng);
                    },
                    // Error callback
                    function(error) {
                        console.error('Error getting location:', error.message);

                        // Handle different types of geolocation errors
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                console.log('User denied the request for Geolocation.');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                console.log('Location information is unavailable.');
                                break;
                            case error.TIMEOUT:
                                console.log('The request to get user location timed out.');
                                break;
                            default:
                                console.log('An unknown error occurred.');
                                break;
                        }

                        // Use fallback coordinates
                        console.log('Using fallback location');
                        createMap(fallbackLat, fallbackLng);
                    },
                    // Options
                    {
                        enableHighAccuracy: true,
                        timeout: 10000, // 10 seconds timeout
                        maximumAge: 300000 // Accept cached position up to 5 minutes old
                    }
                );
            } else {
                // Geolocation is not supported by this browser
                console.log('Geolocation is not supported by this browser.');
                createMap(fallbackLat, fallbackLng);
            }
        }
        // Setup event listeners
        function setupEventListeners() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const thresholdInput = document.getElementById('threshold');
            const saveBtn = document.getElementById('saveLocationBtn');
            const resetBtn = document.getElementById('resetLocationBtn');

            // Update marker when input fields change
            latInput.addEventListener('change', updateMarkerFromInputs);
            lngInput.addEventListener('change', updateMarkerFromInputs);

            // Update threshold circle when threshold changes
            thresholdInput.addEventListener('input', function() {
                const radius = parseInt(thresholdInput.value) || 100;
                thresholdCircle.setRadius(radius);
                document.getElementById('threshold-display').textContent = radius + 'm';
            });

            // Save location
            saveBtn.addEventListener('click', function() {
                saveLocation();
            });

            // Reset location
            resetBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the location? This will remove the current location settings.')) {
                    resetLocation();
                }
            });
        }

        // Update marker from input fields
        function updateMarkerFromInputs() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                thresholdCircle.setLatLng([lat, lng]);
                map.setView([lat, lng], 13);
            }
        }

        // Save location
        function saveLocation() {
            const form = document.getElementById('locationForm');
            const formData = new FormData(form);

            // Validate inputs
            const latitude = formData.get('latitude');
            const longitude = formData.get('longitude');
            const threshold = formData.get('threshold');

            if (!latitude || !longitude || !threshold) {
                alert('Please fill in all fields');
                return;
            }

            // Send AJAX request to save location
            fetch('{{ route("admin.attendance.location.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    institute_id: formData.get('institute_id'),
                    latitude: parseFloat(latitude),
                    longitude: parseFloat(longitude),
                    threshold: parseInt(threshold)
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update status
                        document.getElementById('location-status').innerHTML = '<span class="text-green-600 dark:text-green-400">Configured</span>';
                        document.getElementById('last-updated').textContent = new Date().toLocaleString();

                        // Show success message
                        alert('Location saved successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the location.');
                });
        }

        // Reset location
        function resetLocation() {
            const form = document.getElementById('locationForm');
            const formData = new FormData(form);

            // Send AJAX request to reset location
            fetch('{{ route("admin.attendance.location.delete") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    institute_id: formData.get('institute_id')
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear form fields
                        document.getElementById('latitude').value = '';
                        document.getElementById('longitude').value = '';
                        document.getElementById('threshold').value = '100';

                        // Update status
                        document.getElementById('location-status').innerHTML = '<span class="text-red-600 dark:text-red-400">Not Configured</span>';
                        document.getElementById('threshold-display').textContent = '100m';
                        document.getElementById('last-updated').textContent = 'Never';

                        // Reset map
                        map.setView([37.7749, -122.4194], 13);
                        marker.setLatLng([37.7749, -122.4194]);
                        thresholdCircle.setLatLng([37.7749, -122.4194]);
                        thresholdCircle.setRadius(100);

                        // Show success message
                        alert('Location reset successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while resetting the location.');
                });
        }
    </script>
@endsection
