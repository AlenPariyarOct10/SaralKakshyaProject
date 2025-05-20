@extends("backend.layout.auth")

@section("logo")
    <div class="flex justify-center">
        <img class="h-20" src="{{asset($system_info['logo'])}}" alt="" srcset="">
    </div>
@endsection

@section("title", "Reset Password - ".$system_info['name'])
@section("name", $system_info['name'])
@section("description", $system_info['description'])
@section("content")
    <div class="p-6">
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

        <h2 class="text-2xl font-semibold text-gray-800 text-center">Reset Password</h2>
        <p class="text-sm mt-1 mb-6 text-center">Create a new password for your account</p>

        <form action="{{ route('password.update') }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                Reset Password
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
