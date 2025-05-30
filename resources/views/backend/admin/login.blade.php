@extends("backend.layout.auth")

@section("logo")
    <div class="flex justify-center">
        <img class="h-20" src="{{asset($system_info['logo'])}}" alt="" srcset="">
    </div>

@endsection

@section("title", "Login - ".$system_info['name'])
@section("name", $system_info['name'])
@section("description", $system_info['description'])
@section("content")
    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success !</strong>
                <span class="block sm:inline">{{session('success')}}</span>
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
        <h2 class="text-2xl font-semibold text-gray-800 text-center">Login to Your Account</h2>
        <p class="text-sm mt-1 mb-6 text-center">Admin Account</p>

        <form action="{{route("admin.login")}}" method="post" id="loginForm">
            @csrf
            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-envelope"></i>
                            </span>
                    <input type="email" id="email" name="email" value="{{old('email')}}"
                           class="w-full py-2 pl-10 pr-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter your email" required>
                </div>
                <p id="emailError" class="text-red-500 text-xs mt-1 hidden">Please enter a valid email address</p>
            </div>
            @component('components.login.institute-input', ['institutes' => $institutes]) @endcomponent


            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                    <input type="password" id="password" name="password"
                           class="w-full py-2 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter your password" required>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <p id="passwordError" class="text-red-500 text-xs mt-1 hidden">Password must be at least 6 characters</p>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <a href="{{route('admin.forgot-password')}}" class="text-sm text-primary hover:text-secondary">Forgot password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                Login
            </button>


            <!-- Divider -->
            <div class="relative flex items-center mt-8 mb-6">
                <div class="flex-grow border-t border-gray-300"></div>

            </div>
            <!-- Login Link -->
            <p class="text-center text-gray-600 text-sm">
                Create an account?
                <a href="{{route('admin.register')}}" class="text-primary hover:text-secondary font-medium">Register</a>
            </p>
        </form>
    </div>

@endsection
