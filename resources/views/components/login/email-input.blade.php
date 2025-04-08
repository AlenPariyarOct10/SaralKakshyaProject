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
