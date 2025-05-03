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
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
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
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Create Student Account</h2>

            <form action="{{route("student.register")}}" method="post" id="signupForm">

                @csrf
                <!-- Name Inputs -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="firstName" class="block text-gray-700 text-sm font-medium mb-2">First Name</label>
                        <input type="text" id="firstName" name="fname"
                               class="w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="First name" required>
                        <p id="firstNameError" class="text-red-500 text-xs mt-1 {{ $errors->has('fname') ? '' : 'hidden' }}">
                            {{ $errors->first('fname') }}
                        </p>
                    </div>
                    <div>
                        <label for="lastName" class="block text-gray-700 text-sm font-medium mb-2">Last Name</label>
                        <input type="text" id="lastName" name="lname"
                               class="w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Last name" required>
                        <p id="lastNameError" class="text-red-500 text-xs mt-1 {{ $errors->has('lname') ? '' : 'hidden' }}">
                            {{ $errors->first('lname') }}
                        </p>
                    </div>
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
                    <p id="emailError" class="text-red-500 text-xs mt-1 {{ $errors->has('email') ? '' : 'hidden' }}">
                        {{ $errors->first('email') }}
                    </p>
                </div>

                @component('components.login.institute-input', compact("institutes")) @endcomponent


                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                        <input type="password" id="password" name="password"
                               class="w-full py-2 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Create a password" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p id="passwordError" class="text-red-500 text-xs mt-1 {{ $errors->has('password') ? '' : 'hidden' }}">
                        {{ $errors->first('password') }}
                    </p>
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-6">
                    <label for="confirmPassword" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                    <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                        <input type="password" id="confirmPassword" name="password_confirmation"
                               class="w-full py-2 pl-10 pr-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Confirm your password" required>
                    </div>
                    <p id="confirmPasswordError" class="text-red-500 text-xs mt-1 {{ $errors->has('password_confirmation') ? '' : 'hidden' }}">
                        {{ $errors->first('password_confirmation') }}
                    </p>
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
