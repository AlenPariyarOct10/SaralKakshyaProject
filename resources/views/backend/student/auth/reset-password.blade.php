@extends("backend.layout.auth")

@section("logo")
    <div class="flex justify-center">
        <img class="h-20" src="{{asset($system_info['logo'])}}" alt="" srcset="">
    </div>
@endsection
@section("title", "Verify OTP - ".$system_info['name'])
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

        <h2 class="text-2xl font-semibold text-gray-800 text-center">Verify OTP</h2>
        <p class="text-sm mt-1 mb-6 text-center">Enter the verification code sent to your email</p>

        <form action="{{ route('student.verify.otp') }}" method="post">
            @csrf
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <!-- OTP Input -->
            <div class="mb-6">
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                <div class="flex justify-center gap-2">
                    @for ($i = 1; $i <= 6; $i++)
                        <input type="text"
                               name="otp[]"
                               maxlength="1"
                               class="w-12 h-12 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary text-xl"
                               required
                               autocomplete="off">
                    @endfor
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-primary bg-blue-500 hover:bg-secondary text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out mt-4">
                Verify
            </button>
        </form>

        <!-- Resend OTP -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 mb-2">Didn't receive the code?</p>
            <form action="{{ route('student.resend.otp') }}" method="post">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                <button type="submit" class="text-primary hover:text-secondary text-sm font-medium">
                    Resend Code
                </button>
            </form>
        </div>

        <!-- Back to Login Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('student.login') }}" class="text-primary hover:text-secondary text-sm">
                Back to Login
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('input[name="otp[]"]');

            // Focus on first input on page load
            otpInputs[0].focus();

            // Auto-focus next input after entering a digit
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === this.maxLength && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });

                // Handle backspace to go to previous input
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
@endsection
