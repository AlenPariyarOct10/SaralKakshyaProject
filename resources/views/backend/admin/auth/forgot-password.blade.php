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
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
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

        <h2 class="text-2xl font-semibold text-gray-800 text-center">Forgot Password</h2>
        <p class="text-sm mt-1 mb-6 text-center">Enter your email to reset your password</p>

        <form action="{{ route('admin.password.email') }}" method="post">
            @csrf

            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                       placeholder="Enter your email">
            </div>

            <!-- Institute Input -->
            @component('components.login.institute-input', ['institutes' => $institutes]) @endcomponent

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-primary bg-blue-500 hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out mt-6">
                Send Reset Link
            </button>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('admin.login') }}" class="text-primary hover:text-secondary text-sm">
                Back to Login
            </a>
        </div>
    </div>
@endsection
