@php
    $system = \App\Models\SystemSetting::first();
@endphp
<footer class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 pb-6">
    <p>© {{date('Y')}} {{$system->name}}. All rights reserved.</p>
</footer>
