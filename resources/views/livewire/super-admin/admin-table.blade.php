<div>
<!-- Admin Management Tabs -->
<div class="mb-6">
    <ul class="flex flex-wrap -mb-px">
        <li class="mr-2 cursor-pointer">
            <a wire:click="setTab('all')" class="tab {{($activeTab === 'all')?'active':''}}" data-tab="all">All Admins</a>
        </li>
        <li class="mr-2 cursor-pointer">
            <a wire:click="setTab('pending')" class="tab {{($activeTab === 'pending')?'active':''}}" data-tab="pending">Pending Approval <span class="ml-1 text-xs bg-yellow-500 text-white rounded-full px-2 py-0.5">{{$pendingApproval}}</span></a>
        </li>
        <li class="mr-2 cursor-pointer">
            <a wire:click="setTab('approved')" class="tab {{($activeTab === 'approved')?'active':''}}" data-tab="approved">Approved</a>
        </li>
        <li class="mr-2 cursor-pointer">
            <a wire:click="setTab('trashed')" class="tab {{($activeTab === 'trashed')?'active':''}}" data-tab="trashed">Trashed</a>
        </li>
    </ul>
</div>

<!-- Search and Filter -->
<div class="flex flex-col md:flex-row gap-4 mb-6">
    <div class="flex-1">

        <div class="relative w-full max-w-sm">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input
                type="text"
                id="admin-search"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                placeholder="Search admins..."
                wire:model.debounce.300ms="search"
            />
        </div>

    </div>
</div>


    <!-- All Admins Table -->
    @if($activeTab === 'all')
        <h2 class="text-lg font-semibold mb-2 text-black dark:text-white">All Records</h2>
        @include('components.backend.admin-table', ['admins' => $admins])
    @endif

    <!-- Pending Table -->
    @if($activeTab === 'pending')
        <h2 class="text-lg font-semibold mb-2 text-black dark:text-white">Pending Approval</h2>
        @include('components.backend.admin-table', ['admins' => $admins])
    @endif

    <!-- Approved Table -->
    @if($activeTab === 'approved')
        <h2 class="text-lg font-semibold mb-2 text-black dark:text-white">Approved Admins</h2>
        @component('components.backend.admin-table', ['admins' => $admins]) @endcomponent
    @endif

    <!-- Approved Table -->
    @if($activeTab === 'trashed')
        <h2 class="text-lg font-semibold mb-2 text-black dark:text-white">Trashed Admins</h2>
        @component('components.backend.admin-table', ['admins' => $admins]) @endcomponent
    @endif
</div>

</div>
