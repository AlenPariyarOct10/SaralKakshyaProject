
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
    <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
        <strong class="font-bold">Success !</strong>
        <span class="block sm:inline">Account created, Login to continue</span>
    </div>
    @endif
    @component('components.login.validation-summary') @endcomponent

        <h2 class="text-2xl font-semibold text-gray-800 text-center">Login to Your Account</h2>
    <p class="text-sm mt-1 mb-6 text-center">Super Admin Login</p>

    <form action="{{route('superadmin.login')}}" method="post" id="loginForm">
        @csrf
        <!-- Email Input -->
        @component('components.login.email-input') @endcomponent
        <!-- Password Input -->
        @component('components.login.password-input') @endcomponent

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <a href="#" class="text-sm text-primary hover:text-secondary">Forgot password?</a>
        </div>
        <!-- Login Button -->
        <button type="submit" class="w-full bg-primary bg-blue-500 hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
            Login
        </button>
    </form>
</div>

@endsection
