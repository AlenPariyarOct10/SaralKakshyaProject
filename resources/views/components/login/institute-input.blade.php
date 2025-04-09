<div class="mb-4">
    <label for="institute" class="block text-sm font-medium text-gray-700">Institute</label>
    <select name="institute" id="institute" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <option value="" disabled {{ old('institute') ? '' : 'selected' }}>Select Institute</option>
        @foreach ($institutes as $institute)
            <option value="{{ $institute->id }}" {{ old('institute') == $institute->id ? 'selected' : '' }}>
                {{ $institute->name }}, {{$institute->address}}
            </option>
        @endforeach
    </select>

    <!-- Error message for Institute -->
    <p id="instituteError" class="text-red-500 text-xs mt-1 {{ $errors->has('institute') ? '' : 'hidden' }}">
        {{ $errors->first('institute') }}
    </p>
</div>
