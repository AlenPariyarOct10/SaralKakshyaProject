    @if(session('success'))
        <div class="p-4 mb-2 border-l-4 border-green-500 bg-red-50 dark:bg-green-900/20 rounded-md">
            <div class="flex items-center gap-3">
                <!-- Icon -->
                <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 dark:bg-green-800">
            <i class="fa-solid fa-circle-check text-green-600 dark:text-green-300"></i>
        </span>
                <div>
                    <h4 class="text-md font-medium text-gray-800 dark:text-white">{{ session('success') }}</h4>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-2 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-md">
            <div class="flex items-center gap-3">
                <!-- Icon -->
                <span class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-800">
            <i class="fas fa-exclamation-circle text-red-600 dark:text-red-300"></i>
        </span>
                <div>
                    <h4 class="text-md font-medium text-red-800 dark:text-white">{{ session('error') }}</h4>
                </div>
            </div>
        </div>
    @endif
