@extends('backend.layout.auth')

@section('title', 'Complete Profile')
@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const departmentSelect = document.getElementById('department');
            const programSelect = document.getElementById('program');
            const batchSelect = document.getElementById('batch');
            const sectionSelect = document.getElementById('section');

            // Department change handler
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;

                // Clear dependent fields
                programSelect.innerHTML = '<option value="">Select Program</option>';
                batchSelect.innerHTML = '<option value="">Select Batch</option>';
                sectionSelect.innerHTML = '<option value="">Select Section</option>';

                if (departmentId) {
                    // Fetch programs based on selected department
                    fetch(`/student/department/${departmentId}/programs`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(program => {
                                const option = document.createElement('option');
                                option.value = program.id;
                                option.textContent = program.name;
                                programSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching programs:', error));
                }
            });

            // Program change handler
            programSelect.addEventListener('change', function() {
                const programId = this.value;
                const departmentId = departmentSelect.value;

                // Clear dependent fields
                batchSelect.innerHTML = '<option value="">Select Batch</option>';
                sectionSelect.innerHTML = '<option value="">Select Section</option>';

                if (programId && departmentId) {
                    // Fetch batches based on selected program and department
                    fetch(`/student/program/batches?program_id=${programId}&department_id=${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(batch => {
                                const option = document.createElement('option');
                                option.value = batch.id;
                                option.textContent = batch.batch;
                                batchSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching batches:', error));

                    // Fetch sections based on selected program
                    fetch(`/student/program/${programId}/sections`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.section_name;
                                sectionSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching sections:', error));
                }
            });
            // Helper function to show errors
            function showError(input, message) {
                input.classList.add('border-red-500');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'date-error text-red-500 text-xs mt-1';
                errorDiv.textContent = message;
                input.parentNode.appendChild(errorDiv);
            }

            // Add validation on form submit
            document.getElementById('signupForm').addEventListener('submit', function (event) {
                if (!validateDates()) {
                    event.preventDefault();
                }
            });

            document.getElementById('dob').addEventListener('change', validateDates);
            document.getElementById('admission_date').addEventListener('change', validateDates);

            function validateDates() {
                const dobInput = document.getElementById('dob');
                const admissionInput = document.getElementById('admission_date');
                const today = new Date();
                let isValid = true;

                // Reset previous errors
                dobInput.classList.remove('border-red-500');
                admissionInput.classList.remove('border-red-500');
                document.querySelectorAll('.date-error').forEach(el => el.remove());

                // Validate DOB
                if (dobInput.value) {
                    const dobDate = new Date(dobInput.value);
                    const minDobDate = new Date(today.getFullYear() - 15, today.getMonth(), today.getDate());

                    if (dobDate > today) {
                        showError(dobInput, 'Date of birth cannot be in the future');
                        isValid = false;
                    } else if (dobDate > minDobDate) {
                        showError(dobInput, 'You must be at least 15 years old');
                        isValid = false;
                    }
                }

                // Validate Admission Date
                if (admissionInput.value) {
                    const admissionDate = new Date(admissionInput.value);
                    const dobDate = dobInput.value ? new Date(dobInput.value) : null;

                    if (admissionDate > today) {
                        showError(admissionInput, 'Admission date cannot be in the future');
                        isValid = false;
                    } else if (dobDate && admissionDate < dobDate) {
                        showError(admissionInput, 'Admission date cannot be before date of birth');
                        isValid = false;
                    }
                }

                return isValid;
            }

            // Age validation
            document.getElementById('signupForm').addEventListener('submit', function (event) {
                const dobInput = document.getElementById('dob');
                const dobValue = new Date(dobInput.value);
                const currentDate = new Date();
                const ageLimit = 15;
                const ageDate = new Date(currentDate.getFullYear() - ageLimit, currentDate.getMonth(), currentDate.getDate());

                if (dobValue > ageDate) {
                    event.preventDefault();
                    alert('You must be at least 15 years old to register.');
                    dobInput.focus();
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Complete Your Profile</h2>
            <form id="signupForm" action="{{ route('student.complete-profile.post') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="fname" class="block text-gray-700 text-sm font-medium mb-2">First Name</label>
                    <input type="text" id="fname" name="fname" value="{{ $studentData['fname'] }}" class="w-full py-2 px-3 border rounded-md" readonly>
                </div>

                <div class="mb-4">
                    <label for="lname" class="block text-gray-700 text-sm font-medium mb-2">Last Name</label>
                    <input type="text" id="lname" name="lname" value="{{ $studentData['lname'] }}" class="w-full py-2 px-3 border rounded-md" readonly>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ $studentData['email'] }}" class="w-full py-2 px-3 border rounded-md" readonly>
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Address</label>
                    <textarea id="address" name="address" class="w-full py-2 px-3 border rounded-md" required></textarea>
                </div>

                <!-- Gender -->
                <div class="mb-4">
                    <label for="gender" class="block text-gray-700 text-sm font-medium mb-2">Gender</label>
                    <select id="gender" name="gender" class="w-full py-2 px-3 border rounded-md" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div class="mb-4">
                    <label for="dob" class="block text-gray-700 text-sm font-medium mb-2">Date of Birth</label>
                    <input type="date" id="dob" name="dob" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Guardian Name -->
                <div class="mb-4">
                    <label for="guardian_name" class="block text-gray-700 text-sm font-medium mb-2">Guardian's Name</label>
                    <input type="text" id="guardian_name" name="guardian_name" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Guardian Phone -->
                <div class="mb-4">
                    <label for="guardian_phone" class="block text-gray-700 text-sm font-medium mb-2">Guardian's Phone</label>
                    <input type="text" id="guardian_phone" name="guardian_phone" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Roll Number -->
                <div class="mb-4">
                    <label for="roll_number" class="block text-gray-700 text-sm font-medium mb-2">Roll Number</label>
                    <input type="text" id="roll_number" name="roll_number" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Department -->
                <div class="mb-4">
                    <label for="department" class="block text-gray-700 text-sm font-medium mb-2">Department</label>
                    <select id="department" name="department_id" class="w-full py-2 px-3 border rounded-md" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Program -->
                <div class="mb-4">
                    <label for="program" class="block text-gray-700 text-sm font-medium mb-2">Program</label>
                    <select id="program" name="program_id" class="w-full py-2 px-3 border rounded-md" required>
                        <option value="">Select Program</option>
                    </select>
                </div>

                <!-- Batch -->
                <div class="mb-4">
                    <label for="batch" class="block text-gray-700 text-sm font-medium mb-2">Batch</label>
                    <select id="batch" name="batch_id" class="w-full py-2 px-3 border rounded-md" required>
                        <option value="">Select Batch</option>
                    </select>
                </div>

                <!-- Section -->
                <div class="mb-4">
                    <label for="section" class="block text-gray-700 text-sm font-medium mb-2">Section</label>
                    <select id="section" name="section_id" class="w-full py-2 px-3 border rounded-md" required>
                        <option value="">Select Section</option>
                    </select>
                </div>

                <!-- Admission Date -->
                <div class="mb-4">
                    <label for="admission_date" class="block text-gray-700 text-sm font-medium mb-2">Admission Date</label>
                    <input type="date" id="admission_date" name="admission_date" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Profile Picture -->
                <div class="mb-4">
                    <label for="profile_picture" class="block text-gray-700 text-sm font-medium mb-2">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="w-full py-2 px-3 border rounded-md">
                </div>

                <!-- Submit Button -->
                <div class="mb-6">
                    <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md">
                        Complete Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
