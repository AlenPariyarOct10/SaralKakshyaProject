<!-- Pending Approvals -->
<div class="card">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{session('success')}}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3"></span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
            <strong class="font-bold">Failed!</strong>
            <span class="block sm:inline">{{session('error')}}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3"></span>
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pending Approvals ({{$pendinglist->count()}})</h3>
{{--        <a href="notifications.html" class="text-sm text-primary-600 hover:underline">View all</a>--}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">

                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="admin-search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="Search ..." wire:model.live="search">
                </div>

            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($pendinglist as $item)
        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
            <div class="p-2 bg-yellow-100 dark:bg-yellow-800 rounded-md mr-3">
                <i class="fas fa-user-shield text-yellow-500 dark:text-yellow-300"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium text-gray-800 dark:text-white">{{$item->fname." ".$item->lname}}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    @if ($item->created_at->isToday())
                        {{ $item->created_at->diffForHumans() }}
                    @else
                        {{ $item->created_at->format('M d, Y') }}
                    @endif
                    - {{$item->email}}</p>
            </div>
            <div class="flex space-x-2">
                <button wire:click="approveAdmin({{$item->id}})" class="px-2 py-1 text-xs bg-green-500 text-white rounded-md hover:bg-green-600">
                    <i class="fas fa-check"></i>
                </button>
                <button wire:click="deleteAdmin({{$item->id}})" class="px-2 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @empty
            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                <div class="px-2 py-2 bg-green-100 dark:bg-green-800 rounded-md mr-3">
                    <i class="fas fa-times text-green-500 dark:text-green-300"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white">No Pending Approvals</h4>
                </div>
            </div>
        @endforelse
    </div>
</div>
