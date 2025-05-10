@php use App\Models\Teacher;use Illuminate\Support\Facades\Auth; @endphp
@extends("backend.layout.teacher-dashboard-layout")

@php
    $user = Auth::user();
    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    // Get teacher's availability - fixed query
    $teacher = Teacher::where('id', $user->id)->first();


    $availabilities = $teacher ? $teacher->availabilities()->get() : collect();

    // Group availabilities by day
    $availabilityByDay = [];
    foreach ($days as $day) {
        $availabilityByDay[$day] = $availabilities->where('day_of_week', strtolower($day))->values();
    }

@endphp

@section('username')
    {{$user->fname}} {{$user->lname}}
@endsection

@section('fname')
    {{$user->fname}}
@endsection

@section('lname')
    {{$user->lname}}
@endsection

@section('profile_picture')
    {{$user?->profile_picture}}
@endsection

@section("title")
    Availability Schedule
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 overflow-auto pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            {{-- Page Header --}}
            <div class="flex items-center justify-between mb-6">
                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Availability Schedule</h1>
                    <p class="text-gray-600 mt-1 dark:text-gray-400">Set your weekly availability for classes</p>
                </div>
                <div class="mb-8">
                    <a href="{{route('teacher.profile.index')}}"
                       class="px-5 py-2.5 bg-blue-600 text-white dark:text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 inline-flex items-center">
                        <i class="fa-solid fa-arrow-left pr-2"></i>
                        Go Back
                    </a>
                </div>
            </div>
            {{-- Availability Card --}}
          
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-lg overflow-hidden transition-all duration-300 hover:shadow-md dark:hover:shadow-xl mb-8">
                {{-- Card Header --}}
                <div
                    class="relative bg-gradient-to-r from-blue-700 to-indigo-600 text-white p-6 dark:from-blue-600 dark:to-indigo-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold dark:text-white">Weekly Schedule</h2>
                            <p class="text-white/80 text-sm mt-1 dark:text-white/70">Configure which days and times
                                you're available.</p>
                        </div>
                    </div>

                    {{-- Decorative Element --}}
                    <div
                        class="absolute right-0 bottom-0 opacity-10 dark:opacity-20 transform translate-x-1/4 translate-y-1/4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="240" height="240" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>

                {{-- Availability Form --}}
                <div class="p-6 md:p-8 dark:text-gray-300">
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Set Your
                                    Availability</h3>
                                <p class="text-gray-600 text-sm mt-1 dark:text-gray-400">Select the days and times when
                                    you're available</p>
                            </div>
                        </div>
                    </div>

                    <form id="availability-form" action="{{route('teacher.availability.store')}}" method="POST"
                          class="space-y-8">
                        @csrf
                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                        <div id="status-message" class="hidden"></div>

                        <div class="space-y-6">
                            @foreach($days as $index => $day)
                                <div class="availability-day border border-gray-200 rounded-lg overflow-hidden"
                                     data-day="{{ $day }}"
                                     data-day-enabled="{{ $availabilityByDay[$day]->count() > 0 ? 'true' : 'false' }}">

                                    {{-- Day Header --}}
                                    <div
                                        class="day-header flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="enabled_days[]" value="{{ $day }}"
                                                       class="day-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition-colors"
                                                    {{ $availabilityByDay[$day]->count() > 0 ? 'checked' : '' }}>
                                                <span
                                                    class="ml-2 text-gray-700 dark:text-gray-300 font-medium">{{ $day }}</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center dark:text-gray-300">
                                            <button type="button"
                                                    class="add-time-slot text-sm px-3 py-1.5 rounded-md bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200 flex items-center space-x-1 {{ $availabilityByDay[$day]->count() > 0 ? '' : 'opacity-50 pointer-events-none' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                <span>Add Time Slot</span>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Time Slots Container --}}
                                    <div
                                        class="time-slots-container p-4 space-y-3 {{ $availabilityByDay[$day]->count() > 0 ? '' : 'hidden' }} dark:text-gray-300">
                                        @if($availabilityByDay[$day]->count() > 0)
                                            @foreach($availabilityByDay[$day] as $slot)
                                                <div
                                                    class="time-slot-row flex flex-wrap md:flex-nowrap items-end gap-3 group animate-fade-in">
                                                    <div class="w-full md:w-5/12">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start
                                                            Time</label>
                                                        <input type="time"
                                                               name="availability[{{ $day }}][{{ $loop->index }}][start_time]"
                                                               value="{{ Carbon\Carbon::parse($slot->start_time)->format('H:i') }}"
                                                               class="time-input start-time block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                                    </div>
                                                    <div class="w-full md:w-5/12">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">End
                                                            Time</label>
                                                        <input type="time"
                                                               name="availability[{{ $day }}][{{ $loop->index }}][end_time]"
                                                               value="{{ Carbon\Carbon::parse($slot->end_time)->format('H:i') }}"
                                                               class="time-input end-time block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                                    </div>
                                                    <div class="w-full md:w-2/12 flex justify-end md:justify-center">
                                                        <button type="button"
                                                                class="remove-time-slot p-2.5 text-gray-500 dark:text-gray-300 hover:text-red-500 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div
                                                class="time-slot-row flex flex-wrap md:flex-nowrap items-end gap-3 group animate-fade-in">
                                                <div class="w-full md:w-5/12">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start
                                                        Time</label>
                                                    <input type="time"
                                                           name="availability[{{ $day }}][0][start_time]"
                                                           value="10:00"
                                                           class="time-input start-time block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                                </div>
                                                <div class="w-full md:w-5/12">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">End
                                                        Time</label>
                                                    <input type="time"
                                                           name="availability[{{ $day }}][0][end_time]"
                                                           value="15:30"
                                                           class="time-input end-time block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                                </div>
                                                <div class="w-full md:w-2/12 flex justify-end md:justify-center">
                                                    <button type="button"
                                                            class="remove-time-slot p-2.5 text-gray-500 dark:text-gray-300 hover:text-red-500 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Empty State --}}
                                    <div
                                        class="empty-state p-6 flex justify-center dark:text-gray-400 {{ $availabilityByDay[$day]->count() > 0 ? 'hidden' : '' }}">
                                        <div class="text-center text-gray-500 dark:text-gray-400">
                                            <div
                                                class="inline-flex items-center justify-center p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm dark:text-gray-300">Not available on {{ $day }}s</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Save Button --}}
                        <div class="flex justify-end pt-4">
                            <div class="inline-flex items-center">
                                <span id="save-status"
                                      class="text-sm text-gray-500 dark:text-gray-400 mr-3 opacity-0 transition-opacity duration-300">
                                    Changes saved successfully
                                </span>
                                <button type="submit" id="save-button"
                                        class="px-5 py-2.5 bg-blue-600 dark:bg-blue-700 text-white font-medium rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Help Card --}}
            <div class="bg-blue-50 dark:bg-gray-800 border border-blue-100 dark:border-gray-700 rounded-xl p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-blue-800">Availability Tips</h3>
                        <div class="mt-2 text-sm text-blue-700 space-y-1">
                            <p>• Check the days you're available to teach</p>
                            <p>• Add time slots for each period you're available during those days</p>
                            <p>• You can add multiple time slots per day (e.g., morning and evening)</p>
                            <p>• Changes are saved automatically when you modify your schedule</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Fade in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* Time slot hover effect */
        .time-slot-row:hover {
            background-color: rgba(243, 244, 246, 0.5);
            border-radius: 0.5rem;
        }

        /* Smooth transitions */
        .time-slots-container {
            transition: all 0.3s ease-in-out;
        }

        .day-checkbox {
            transition: all 0.2s ease;
        }

        /* Success flash animation */
        @keyframes successFlash {
            0% {
                opacity: 0;
            }
            25% {
                opacity: 1;
            }
            75% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        .flash-success {
            animation: successFlash 3s ease-in-out forwards;
        }

        /* Error message styling */
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize availability component
            initAvailabilityScheduler();

            function initAvailabilityScheduler() {
                const form = document.getElementById('availability-form');
                const dayContainers = document.querySelectorAll('.availability-day');
                const saveStatus = document.getElementById('save-status');
                const saveButton = document.getElementById('save-button');
                const statusMessage = document.getElementById('status-message');

                // Initialize each day container
                dayContainers.forEach(container => {
                    const day = container.dataset.day;
                    const checkbox = container.querySelector('.day-checkbox');
                    const timeSlotContainer = container.querySelector('.time-slots-container');
                    const emptyState = container.querySelector('.empty-state');
                    const addButton = container.querySelector('.add-time-slot');

                    // Day checkbox event handler
                    checkbox.addEventListener('change', function () {
                        if (this.checked) {
                            emptyState.classList.add('hidden');
                            timeSlotContainer.classList.remove('hidden');
                            addButton.classList.remove('opacity-50', 'pointer-events-none');

                            // If no time slots, add default one
                            if (timeSlotContainer.querySelectorAll('.time-slot-row').length === 0) {
                                addTimeSlot(day, timeSlotContainer);
                            }
                        } else {
                            emptyState.classList.remove('hidden');
                            timeSlotContainer.classList.add('hidden');
                            addButton.classList.add('opacity-50', 'pointer-events-none');
                        }

                        // Save changes
                        debounce(submitForm, 500)();
                    });

                    // Add time slot button event handler
                    addButton.addEventListener('click', function () {
                        addTimeSlot(day, timeSlotContainer);
                        updateTimeSlotIndices(timeSlotContainer, day);
                        attachTimeSlotEvents(timeSlotContainer);
                    });

                    // Initialize time slot event handlers
                    attachTimeSlotEvents(timeSlotContainer);
                });

                // Form submission handler
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitForm();
                });

                // Function to add new time slot
                function addTimeSlot(day, container) {
                    const timeSlotCount = container.querySelectorAll('.time-slot-row').length;
                    const template = `
                    <div class="time-slot-row flex flex-wrap md:flex-nowrap items-end gap-3 group animate-fade-in">
                        <div class="w-full md:w-5/12">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time"
                                   name="availability[${day}][${timeSlotCount}][start_time]"
                                   value="10:00"
                                   class="time-input start-time block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700">
                            <div class="error-message hidden"></div>
                        </div>
                        <div class="w-full md:w-5/12">
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time"
                                   name="availability[${day}][${timeSlotCount}][end_time]"
                                   value="15:30"
                                   class="time-input end-time block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700">
                            <div class="error-message hidden"></div>
                        </div>
                        <div class="w-full md:w-2/12 flex justify-end md:justify-center">
                            <button type="button" class="remove-time-slot p-2.5 text-gray-500 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;

                    // Insert new time slot
                    container.insertAdjacentHTML('beforeend', template);
                }

                // Function to attach events to time slot rows
                function attachTimeSlotEvents(container) {
                    // Remove button event handlers
                    container.querySelectorAll('.remove-time-slot').forEach(button => {
                        button.addEventListener('click', function () {
                            const row = this.closest('.time-slot-row');

                            // Fade out animation before removing
                            row.style.opacity = '0';
                            row.style.transform = 'translateY(10px)';
                            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                            setTimeout(() => {
                                row.remove();

                                // Get day and update indices
                                const dayContainer = this.closest('.availability-day');
                                const day = dayContainer.dataset.day;

                                updateTimeSlotIndices(container, day);

                                // If no time slots left, hide the container and uncheck the day
                                if (container.querySelectorAll('.time-slot-row').length === 0) {
                                    const checkbox = dayContainer.querySelector('.day-checkbox');
                                    checkbox.checked = false;
                                    checkbox.dispatchEvent(new Event('change'));
                                }

                                // Save changes
                                debounce(submitForm, 500)();
                            }, 300);
                        });
                    });

                    // Time input change event handlers
                    container.querySelectorAll('.time-input').forEach(input => {
                        input.addEventListener('change', function () {
                            // Validate times
                            validateTimeInputs(this);

                            // Save changes
                            debounce(submitForm, 500)();
                        });
                    });
                }

                // Function to update time slot indices when slots are added/removed
                function updateTimeSlotIndices(container, day) {
                    const timeSlots = container.querySelectorAll('.time-slot-row');
                    timeSlots.forEach((slot, index) => {
                        const startTime = slot.querySelector('.start-time');
                        const endTime = slot.querySelector('.end-time');

                        startTime.name = `availability[${day}][${index}][start_time]`;
                        endTime.name = `availability[${day}][${index}][end_time]`;
                    });
                }

                // Function to validate time inputs
                function validateTimeInputs(inputElement) {
                    const row = inputElement.closest('.time-slot-row');
                    const startInput = row.querySelector('.start-time');
                    const endInput = row.querySelector('.end-time');

                    if (startInput.value >= endInput.value) {
                        // Set end time to start time + 1 hour
                        const startDate = new Date(`2000-01-01T${startInput.value}`);
                        startDate.setHours(startDate.getHours() + 1);

                        // Format time as HH:MM
                        const hours = startDate.getHours().toString().padStart(2, '0');
                        const minutes = startDate.getMinutes().toString().padStart(2, '0');
                        endInput.value = `${hours}:${minutes}`;

                        // Visual feedback
                        endInput.classList.add('border-yellow-500');
                        setTimeout(() => {
                            endInput.classList.remove('border-yellow-500');
                        }, 1000);
                    }
                }

                // Function to clear all error messages
                function clearErrorMessages() {
                    document.querySelectorAll('.error-message').forEach(error => {
                        error.classList.add('hidden');
                        error.textContent = '';
                    });
                }

                // Function to display error messages
                function displayErrorMessages(errors) {
                    clearErrorMessages();
                    Object.keys(errors).forEach(key => {
                        const [day, index, field] = key.match(/availability\.([^.]+)\.(\d+)\.(.+)/)?.slice(1) || [];
                        if (day && index && field) {
                            const slotRow = document.querySelector(`.availability-day[data-day="${day}"] .time-slot-row:nth-child(${parseInt(index) + 1})`);
                            if (slotRow) {
                                const inputDiv = slotRow.querySelector(`.${field}`);
                                const errorDiv = inputDiv.parentElement.querySelector('.error-message');
                                if (errorDiv) {
                                    errorDiv.textContent = errors[key][0];
                                    errorDiv.classList.remove('hidden');
                                }
                            }
                        }
                    });
                }

                // Function to submit form via AJAX
                function submitForm() {
                    const formData = new FormData(form);

                    // Update button state during save
                    saveButton.disabled = true;
                    saveButton.classList.add('opacity-80');

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(error => {
                                    throw error;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Show success message
                            saveStatus.textContent = data.message;
                            saveStatus.classList.add('flash-success');
                            setTimeout(() => {
                                saveStatus.classList.remove('flash-success');
                            }, 3000);

                            // Clear any error messages
                            clearErrorMessages();

                            // Reset button state
                            saveButton.disabled = false;
                            saveButton.classList.remove('opacity-80');
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Show error message
                            saveStatus.textContent = error.message || 'Error saving changes';
                            saveStatus.classList.add('text-red-500', 'flash-success');
                            setTimeout(() => {
                                saveStatus.classList.remove('flash-success', 'text-red-500');
                                saveStatus.classList.add('text-gray-500');
                            }, 3000);

                            // Display validation errors if any
                            if (error.errors) {
                                displayErrorMessages(error.errors);
                            }

                            // Reset button state
                            saveButton.disabled = false;
                            saveButton.classList.remove('opacity-80');
                        });
                }

                // Debounce function to prevent multiple rapid submissions
                function debounce(func, wait) {
                    let timeout;
                    return function (...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }
            }
        });
    </script>
@endsection
