@extends('backend.layout.auth')

@section('title', 'Complete Profile')
@section("scripts")
    <script>
        document.getElementById('signupForm').addEventListener('submit', function (event) {
            const dobInput = document.getElementById('dob');
            const dobValue = new Date(dobInput.value); // Get the user's DOB
            const currentDate = new Date(); // Current date
            const ageLimit = 15; // Minimum required age
            const ageDate = new Date(currentDate.getFullYear() - ageLimit, currentDate.getMonth(), currentDate.getDate());

            // Check if the DOB is greater than the calculated date (age limit)
            if (dobValue > ageDate) {
                event.preventDefault(); // Prevent form submission
                alert('You must be at least 15 years old to register.');
                dobInput.focus();
            }
        });
    </script>
@endsection
@section('content')
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Complete Your Profile</h2>
            <form action="{{ route('student.complete-profile.post') }}" method="POST" enctype="multipart/form-data">
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

                <!-- Batch -->
                <div class="mb-4">
                    <label for="batch" class="block text-gray-700 text-sm font-medium mb-2">Batch</label>
                    <input type="text" id="batch" name="batch" class="w-full py-2 px-3 border rounded-md" required>
                </div>

                <!-- Section -->
                <div class="mb-4">
                    <label for="section" class="block text-gray-700 text-sm font-medium mb-2">Section</label>
                    <input type="text" id="section" name="section" class="w-full py-2 px-3 border rounded-md" required>
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
