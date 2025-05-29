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
        <span class="block sm:inline">Account created, Login to continue</span>
    </div>
    @endif
        @component('components.login.validation-summary')
        @endcomponent



        <h2 class="text-2xl font-semibold text-gray-800 text-center">Login to Your Account</h2>
    <p class="text-sm mt-1 mb-6 text-center">Teacher Account</p>

    <form action="{{route('teacher.login')}}" method="post" id="loginForm">
        @csrf
        <!-- Email Input -->
        @component('components.login.email-input') @endcomponent
        <!-- Institute Input -->
        @component('components.login.institute-input', ['institutes' => $institutes]) @endcomponent
        <!-- Password Input -->
        @component('components.login.password-input') @endcomponent
        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <a href="#" class="text-sm text-primary hover:text-secondary">Forgot password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
            Login
        </button>
    </form>

    <!-- Divider -->
    <div class="relative flex items-center mt-8 mb-6">
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    <!-- Sign Up Link -->
    <p class="text-center text-gray-600 text-sm">
        Don't have an account?
        <a href="{{route("teacher.register")}}" class="text-primary hover:text-secondary font-medium">Sign up</a>
    </p>
</div>

@endsection
