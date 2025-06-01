@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.layout.student-dashboard-layout')
@php $user = Auth::user(); @endphp
@section('username', $user->fname . ' ' . $user->lname)

@section('content')
    program {{ session('program_id') }}
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Page Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Subjects</h1>
                <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                            <input type="text" id="searchInput" placeholder="Search subjects..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects List -->
            @foreach($subjects as $subject)
                <div class="card mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="flex items-center mb-2">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $subject->name }}</h2>
                                <span class="ml-3 px-2 py-1 text-xs font-medium rounded-full bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                {{ $subject->code }}
                            </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ $subject->description }}
                            </p>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <div>
                                    <span class="font-medium">Credits:</span> {{ $subject->credit }}
                                </div>
                                <div>
                                    <span class="font-medium">Semester:</span> {{ $subject->semester }}
                                </div>
                                <div>
                                    <span class="font-medium">Marks:</span> Internal: {{ $subject->max_internal_marks }}, External: {{ $subject->max_external_marks }}
                                </div>
                            </div>
                            <div class="md:mt-0 flex gap-3">
                                <a href="{{ route('student.subject.resources', $subject->id) }}" class="px-2 py-1 mt-4 bg-primary-500 text-white rounded-md hover:bg-primary-600">
                                    <i class="fas fa-book-open mr-1"></i> Resources
                                </a>
                                <a href="{{ route('student.subject.assignments', $subject->id) }}" class="px-2 py-1 mt-4 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    <i class="fas fa-book-open mr-1"></i> Assignments
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            document.getElementById('searchInput').addEventListener('input', filterResources);

            function filterResources() {
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
