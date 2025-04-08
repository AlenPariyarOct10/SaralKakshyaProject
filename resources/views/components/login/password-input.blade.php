<div class="mb-6">
    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
    <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
        <input type="password" id="password" name="password"
               minlength="6"
               maxlength="18"
               class="w-full py-2 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
               placeholder="Enter your password" required>
        <button type="button" id="togglePassword"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
            <i class="fas fa-eye"></i>
        </button>
    </div>
    <p id="passwordError" class="text-red-500 text-xs mt-1 {{ $errors->has('password') ? '' : 'hidden' }}">
        {{ $errors->first('password') }}
    </p>
</div>
