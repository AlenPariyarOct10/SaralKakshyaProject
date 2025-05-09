@extends('backend.layout.auth')

@section('title', 'Register Step 2 - Additional Details')

@section('content')
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Additional Details</h2>

                <form action="{{ route('teacher.register.step2') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone</label>
                        <input type="text" id="phone" name="phone" class="w-full py-2 px-3 border rounded-md" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Address</label>
                        <input type="text" id="address" name="address" class="w-full py-2 px-3 border rounded-md" required>
                    </div>

                    <!-- Gender -->
                    <div class="mb-4">
                        <label for="gender" class="block text-gray-700 text-sm font-medium mb-2">Gender</label>
                        <select id="gender" name="gender" class="w-full py-2 px-3 border rounded-md" required>
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

                    <!-- Qualification -->
                    <div class="mb-4">
                        <label for="qualification" class="block text-gray-700 text-sm font-medium mb-2">Qualification</label>
                        <input type="text" id="qualification" name="qualification" class="w-full py-2 px-3 border rounded-md" required>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-4">
                        <label for="profile_picture" class="block text-gray-700 text-sm font-medium mb-2">Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="w-full py-2 px-3 border rounded-md" required>
                    </div>

                    <!-- Educational Attachment -->
                    <div class="mb-6">
                        <label for="educational_attachment" class="block text-gray-700 text-sm font-medium mb-2">Educational Qualification Attachment</label>
                        <input type="file" id="educational_attachment" name="educational_attachment" class="w-full py-2 px-3 border rounded-md" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
