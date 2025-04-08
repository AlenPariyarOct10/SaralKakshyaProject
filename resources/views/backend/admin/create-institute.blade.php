@extends("backend.layout.auth")


@section("logo")
    <div class="flex justify-center">
        <img class="h-20" src="{{asset($system_info['logo'])}}" alt="" srcset="">
    </div>

@endsection

@section("title", "Register - ".$system_info['name'])
@section("name", $system_info['name'])
@section("description", $system_info['description'])
@section("content")
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-3" role="alert">
            <strong class="font-bold">Failed !</strong>
            <span class="block sm:inline">Coundn't register the account</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <!-- Form -->
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Create Institute Profile</h2>

                <form action="{{route("admin.register.institute.store")}}" method="post" id="signupForm">

                    @csrf
                    <!-- Name Inputs -->
                    <div class="grid gap-4 mb-4">
                        <div>
                            num -> {{$user->id}}
                            <input type="hidden" name="admin_id" value="{{$user->id}}">
                            <label for="institutionName" class="block text-gray-700 text-sm font-medium mb-2">Institution name</label>
                            <input type="text" id="institutionName" name="name"
                                   class="w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Institution name" required>
                            <p id="firstNameError" class="text-red-500 text-xs mt-1 hidden">Institution name is required</p>
                        </div>
                    </div>
                    <div class="mb-4">
                            <label for="institutionAddress" class="block text-gray-700 text-sm font-medium mb-2">Institution address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input type="text" id="institutionAddress" name="address"
                                   class="w-full py-2 pl-10 pr-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Institution Address" required>
                        </div>
                            <p id="firstNameError" class="text-red-500 text-xs mt-1 hidden">Institution address is required</p>

                    </div>

                    <!-- Email Input -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email"
                                   class="w-full py-2 pl-10 pr-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Enter your email" required>
                        </div>
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden">Please enter a valid email address</p>
                    </div>
                    <!-- Institute Description -->
                    <div class="mb-4">
                        <label for="instituteDescription" class="block text-gray-700 text-sm font-medium mb-2">Description</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-info"></i>
                            </span>
                            <input type="text" id="instituteDescription" name="description"
                                   class="w-full py-2 pl-10 pr-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Enter description" required>
                        </div>
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden">Please enter a valid description</p>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                        Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex items-center mt-8 mb-6">
                    <div class="flex-grow border-t border-gray-300"></div>

                </div>
                <!-- Login Link -->
                <p class="text-center text-gray-600 text-sm">
                    Already have an account?
                    <a href="{{route('student.login')}}" class="text-primary hover:text-secondary font-medium">Login</a>
                </p>
            </div>
        </div>


    </div>
@endsection
