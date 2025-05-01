<!DOCTYPE html>
<html>
<head>
    <title>Test Reverb</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js'])
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #3b82f6;
            margin-bottom: 30px;
        }
        .status {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f3f4f6;
            transition: all 0.3s ease;
        }
        .status.connected {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
        }
        .events {
            margin-top: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            min-height: 100px;
            max-height: 300px;
            overflow-y: auto;
        }
        .event-item {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Reverb WebSocket Test</h1>

    <div id="connection-status" class="status">
        Connecting to WebSocket server...
    </div>

    <div class="events" id="events-container">
        <div class="event-item">Waiting for events...</div>
    </div>

    <div style="margin-top: 20px;">
        <button id="test-event-btn" class="btn">Trigger Test Event</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusEl = document.getElementById('connection-status');
        const eventsContainer = document.getElementById('events-container');
        const testEventBtn = document.getElementById('test-event-btn');

        // Clear initial message
        eventsContainer.innerHTML = '';

        // Check if Echo is available
        if (typeof window.Echo === 'undefined') {
            statusEl.textContent = 'Error: Echo is not initialized';
            console.error('Echo is not initialized. Check your JavaScript setup.');
            return;
        }

        // Show connection status
        statusEl.textContent = 'Connected to WebSocket server';
        statusEl.classList.add('connected');

        // Listen for events on the realtime-channel
        window.Echo.channel('realtime-channel')
            .listen('.message-sent', function(data) {
                console.log('Received event:', data);

                const eventEl = document.createElement('div');
                eventEl.className = 'event-item';
                eventEl.textContent = `${new Date().toLocaleTimeString()}: ${JSON.stringify(data)}`;
                eventsContainer.appendChild(eventEl);

                // Auto-scroll to bottom
                eventsContainer.scrollTop = eventsContainer.scrollHeight;
            });

        // Test event button
        testEventBtn.addEventListener('click', function() {
            fetch('/test-event')
                .then(response => response.text())
                .then(data => {
                    console.log('Event triggered:', data);
                    const eventEl = document.createElement('div');
                    eventEl.className = 'event-item';
                    eventEl.textContent = `${new Date().toLocaleTimeString()}: Event triggered manually`;
                    eventsContainer.appendChild(eventEl);
                })
                .catch(error => {
                    console.error('Error triggering event:', error);
                });
        });

        console.log('Listening for events on realtime-channel');
    });
</script>
</body>
</html>
