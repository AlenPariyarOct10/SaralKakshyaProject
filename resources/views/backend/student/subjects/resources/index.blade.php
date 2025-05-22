@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.layout.student-dashboard-layout')
@php $user = Auth::user(); @endphp
@section('username', $user->fname . ' ' . $user->lname)

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Page Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
{{--                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $subject->name }} Resources</h1>--}}
                <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <select id="typeFilter" name="type" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Types</option>
                            <option value="book">Book</option>
                            <option value="video">Video</option>
                            <option value="document">Document</option>
                            <option value="link">Link</option>
                        </select>
                    </div>

                    <div class="relative">
                        <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                            <input type="text" id="searchInput" placeholder="Search resources..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject Info Card -->
{{--            <div class="card mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">--}}
{{--                <div class="flex flex-col md:flex-row md:items-center md:justify-between">--}}
{{--                    <div>--}}
{{--                        <div class="flex items-center mb-2">--}}
{{--                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $subject->name }}</h2>--}}
{{--                            <span class="ml-3 px-2 py-1 text-xs font-medium rounded-full bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">--}}
{{--                                {{ $subject->code }}--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">--}}
{{--                            {{ $subject->description }}--}}
{{--                        </p>--}}
{{--                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">--}}
{{--                            <div>--}}
{{--                                <span class="font-medium">Credits:</span> {{ $subject->credit }}--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <span class="font-medium">Semester:</span> {{ $subject->semester }}--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <span class="font-medium">Marks:</span> Internal: {{ $subject->max_internal_marks }}, External: {{ $subject->max_external_marks }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="mt-4 md:mt-0 flex gap-3">--}}
{{--                        <a href="{{ route('student.subject.show', $subject->id) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">--}}
{{--                            <i class="fas fa-arrow-left mr-1"></i> Back to Subject--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Resources List -->
            <div class="card bg-white dark:bg-gray-800 rounded-lg shadow-sm border dark:border-gray-700">
                <div class="p-6 border-b dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Available Resources</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
{{--                            <span id="resourceCount">{{ count($resources) }}</span> resources found--}}
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="resourcesList">
                        @forelse($resources as $resource)
                            <div class="border dark:border-gray-700 rounded-lg overflow-hidden resource-card" data-type="{{ $resource->type }}">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-md font-medium text-gray-800 dark:text-white">{{ $resource->title }}</h4>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($resource->type == 'book') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($resource->type == 'video') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @elseif($resource->type == 'document') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @else bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                            @endif">
                                            {{ ucfirst($resource->type) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Added: {{ \Carbon\Carbon::parse($resource->created_at)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="p-4 border-t dark:border-gray-700">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                        {{ $resource->description }}
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Views:</span> {{ $resource->views_count }}
                                            <span class="ml-2 font-medium">Downloads:</span> {{ $resource->download_count }}
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('student.resource.show', $resource->id) }}" class="px-3 py-1 text-sm bg-primary-500 text-white rounded-md hover:bg-primary-600">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </a>
                                            <a href="{{ route('student.resource.download', $resource->id) }}" class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <div class="text-gray-400 dark:text-gray-500 mb-2">
                                    <i class="fas fa-file-alt text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">No Resources Found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">There are no resources available for this subject yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if(count($resources) > 0)
                    <div class="p-6 border-t dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Showing <span id="visibleResources">{{ count($resources) }}</span> of <span id="totalResources">{{ count($resources) }}</span> resources
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" id="prevPage" disabled>Previous</button>
                                <button class="px-3 py-1 rounded-md bg-primary-500 text-white hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed" id="nextPage" disabled>Next</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Resource Preview Modal -->
    <div id="resourceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full mx-4 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white" id="modalResourceTitle"></h3>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resource Type</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400" id="modalResourceType"></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400" id="modalResourceDescription"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Added By</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="modalResourceTeacher"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Added</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="modalResourceDate"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Views</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="modalResourceViews"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Downloads</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="modalResourceDownloads"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="#" id="modalDownloadResource" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                    <a href="#" id="modalViewResource" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600">
                        <i class="fas fa-eye mr-1"></i> View Full Resource
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            document.getElementById('typeFilter').addEventListener('change', filterResources);
            document.getElementById('searchInput').addEventListener('input', filterResources);

            function filterResources() {
                const type = document.getElementById('typeFilter').value;
                const search = document.getElementById('searchInput').value.toLowerCase();
                let visibleCount = 0;
                const totalResources = document.querySelectorAll('.resource-card').length;

                document.querySelectorAll('.resource-card').forEach(card => {
                    const resourceType = card.dataset.type;
                    const resourceTitle = card.querySelector('h4').textContent.toLowerCase();
                    const resourceDescription = card.querySelector('.line-clamp-3').textContent.toLowerCase();

                    const matchesType = !type || resourceType === type;
                    const matchesSearch = !search ||
                        resourceTitle.includes(search) ||
                        resourceDescription.includes(search);

                    const visible = matchesType && matchesSearch;
                    card.style.display = visible ? '' : 'none';

                    if (visible) visibleCount++;
                });

                document.getElementById('resourceCount').textContent = visibleCount;
                document.getElementById('visibleResources').textContent = visibleCount;
                document.getElementById('totalResources').textContent = totalResources;

                // Update pagination buttons
                document.getElementById('prevPage').disabled = true;
                document.getElementById('nextPage').disabled = visibleCount <= 9; // Assuming 9 items per page
            }

            // Resource preview modal functionality
            const modal = document.getElementById('resourceModal');
            const closeModal = document.getElementById('closeModal');

            // Close modal when clicking the close button
            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Close modal when clicking outside the modal content
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // View resource buttons (this would be implemented if we had view buttons in the cards)
            document.querySelectorAll('.resource-card').forEach(card => {
                const viewBtn = card.querySelector('a[href*="resource.show"]');

                viewBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const resourceId = this.href.split('/').pop();
                    const resourceTitle = card.querySelector('h4').textContent;
                    const resourceType = card.querySelector('.rounded-full').textContent.trim();
                    const resourceDescription = card.querySelector('.line-clamp-3').textContent.trim();
                    const resourceDate = card.querySelector('p.text-sm').textContent.replace('Added:', '').trim();

                    // Get views and downloads
                    const statsText = card.querySelector('.text-xs').textContent;
                    const viewsMatch = statsText.match(/Views: (\d+)/);
                    const downloadsMatch = statsText.match(/Downloads: (\d+)/);

                    const views = viewsMatch ? viewsMatch[1] : '0';
                    const downloads = downloadsMatch ? downloadsMatch[1] : '0';

                    // Populate modal
                    document.getElementById('modalResourceTitle').textContent = resourceTitle;
                    document.getElementById('modalResourceType').textContent = resourceType;
                    document.getElementById('modalResourceDescription').textContent = resourceDescription;
                    document.getElementById('modalResourceTeacher').textContent = 'Teacher Name'; // This would come from the server
                    document.getElementById('modalResourceDate').textContent = resourceDate;
                    document.getElementById('modalResourceViews').textContent = views;
                    document.getElementById('modalResourceDownloads').textContent = downloads;

                    // Set up action links
                    document.getElementById('modalViewResource').href = this.href;
                    document.getElementById('modalDownloadResource').href = card.querySelector('a[href*="resource.download"]').href;

                    // Show modal
                    modal.classList.remove('hidden');
                });
            });
        });
    </script>
@endsection
