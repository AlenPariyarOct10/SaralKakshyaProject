@extends("backend.layout.admin-dashboard-layout")

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
    {{$user->profile_picture}}
@endsection

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Institute</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update institute information and settings</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('admin.institute.show', $institute->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    <i class="fas fa-eye mr-2"></i> View Institute
                </a>
                <a href="{{ route('admin.institute.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Institutes
                </a>
            </div>
        </div>

        <!-- Edit Institute Form -->
        <div class="card">
            <form action="{{ route('admin.institute.update', $institute->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Institute Information Section -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Institute Information</h2>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Institute Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Institute Name <span class="text-red-600">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ $institute->name }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Institute Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Institute Type</label>
                                    <select name="type" id="type" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <option value="">Select Type</option>
                                        <option value="public" {{ $institute->type == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ $institute->type == 'private' ? 'selected' : '' }}>Private</option>
                                        <option value="charter" {{ $institute->type == 'charter' ? 'selected' : '' }}>Charter</option>
                                        <option value="research" {{ $institute->type == 'research' ? 'selected' : '' }}>Research</option>
                                        <option value="other" {{ $institute->type == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <!-- Website -->
                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                    <input type="url" name="website" id="website" value="{{ $institute->website }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-600">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ $institute->email }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone <span class="text-red-600">*</span></label>
                                    <input type="tel" name="phone" id="phone" value="{{ $institute->phone }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Logo Upload -->
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                                    <div class="mt-1 flex items-center">
                                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                                            @if($institute->logo)
                                                <img src="{{ asset('storage/' . $institute->logo) }}" class="h-12 w-12 rounded-full object-cover" alt="{{ $institute->name }}">
                                            @else
                                                <svg class="h-full w-full text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            @endif
                                        </span>
                                        <input type="file" name="logo" id="logo" accept="image/*" class="ml-5 p-2 focus:ring-primary-500 focus:border-primary-500 block shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select name="status" id="status" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <option value="active" {{ $institute->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $institute->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Address Information</h2>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Address Line 1 -->
                                <div>
                                    <label for="address_line1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 1 <span class="text-red-600">*</span></label>
                                    <input type="text" name="address_line1" id="address_line1" value="{{ $institute->address_line1 }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>

                                <!-- Address Line 2 -->
                                <div>
                                    <label for="address_line2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 2</label>
                                    <input type="text" name="address_line2" id="address_line2" value="{{ $institute->address_line2 }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City <span class="text-red-600">*</span></label>
                                    <input type="text" name="city" id="city" value="{{ $institute->city }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>

                                <!-- State/Province -->
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State/Province <span class="text-red-600">*</span></label>
                                    <input type="text" name="state" id="state" value="{{ $institute->state }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code <span class="text-red-600">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ $institute->postal_code }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>

                                <!-- Country -->
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country <span class="text-red-600">*</span></label>
                                    <select name="country" id="country" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                        <option value="">Select Country</option>
                                        <option value="US" {{ $institute->country == 'US' ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ $institute->country == 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="UK" {{ $institute->country == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="AU" {{ $institute->country == 'AU' ? 'selected' : '' }}>Australia</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Person Section -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Contact Person</h2>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Contact Name -->
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-600">*</span></label>
                                    <input type="text" name="contact_name" id="contact_name" value="{{ $institute->contact_name }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>

                                <!-- Contact Position -->
                                <div>
                                    <label for="contact_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                                    <input type="text" name="contact_position" id="contact_position" value="{{ $institute->contact_position }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                </div>

                                <!-- Contact Email -->
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-600">*</span></label>
                                    <input type="email" name="contact_email" id="contact_email" value="{{ $institute->contact_email }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone <span class="text-red-600">*</span></label>
                                    <input type="tel" name="contact_phone" id="contact_phone" value="{{ $institute->contact_phone }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Additional Information</h2>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" id="description" rows="4" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">{{ $institute->description }}</textarea>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Brief description of the institute and its programs.</p>
                            </div>

                            <!-- Notes -->
                            <div class="mt-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Internal Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white">{{ $institute->notes }}</textarea>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Any additional notes for internal reference.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600 mr-3">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        Update Institute
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Preview image when selecting a file
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.inline-block');
                    preview.innerHTML = `<img src="${e.target.result}" class="h-12 w-12 rounded-full object-cover">`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('border-red-500');

                    // Add error message if not exists
                    let errorMessage = field.parentElement.querySelector('.text-red-600');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('mt-1', 'text-sm', 'text-red-600');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    const errorMessage = field.parentElement.querySelector('.text-red-600');
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
@endsection
