@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Edit Resource')

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <!-- Edit Resource Form -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Resource</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('teacher.resources.show', $resource->id) }}" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400 text-sm">
                        <i class="far fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('teacher.resources.index') }}" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Resources
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-6 rounded relative" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('teacher.resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <!-- Resource Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resource Type</label>
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg mr-3">
                                <i class="fas fa-{{
                                    $resource->type == 'document' ? 'file-alt' :
                                    ($resource->type == 'video' ? 'video' :
                                    ($resource->type == 'audio' ? 'headphones' :
                                    ($resource->type == 'link' ? 'link' : 'folder')))
                                }} text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-medium text-gray-900 dark:text-white">{{ ucfirst($resource->type) }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{
                                        $resource->type == 'document' ? 'PDFs, Word docs, etc.' :
                                        ($resource->type == 'video' ? 'Tutorials, lectures, etc.' :
                                        ($resource->type == 'audio' ? 'Podcasts, recordings, etc.' :
                                        ($resource->type == 'link' ? 'External resources, websites' : 'Miscellaneous resources')))
                                    }}
                                </p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Resource type cannot be changed. Create a new resource if you need a different type.</p>
                    </div>

                    <!-- Basic Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Basic Information</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resource Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $resource->title) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
                                <select id="subject_id" name="subject_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $resource->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">{{ old('description', $resource->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Chapter -->
                            <div>
                                <label for="chapter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chapter</label>
                                <select id="chapter_id" name="chapter_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="">Select Chapter</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ old('chapter_id', $resource->chapter_id) == $chapter->id ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub Chapter -->
                            <div>
                                <label for="sub_chapter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub Chapter</label>
                                <select id="sub_chapter_id" name="sub_chapter_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="">Select Sub Chapter</option>
                                    @foreach($subChapters as $subChapter)
                                        <option value="{{ $subChapter->id }}" {{ old('sub_chapter_id', $resource->sub_chapter_id) == $subChapter->id ? 'selected' : '' }}>
                                            {{ $subChapter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Links Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Links</h4>
                        <div id="links-container">
                            @forelse($resource->links as $index => $link)
                                <div class="link-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-md mb-4">
                                    <input type="hidden" name="links[{{ $index }}][id]" value="{{ $link->id }}">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Title</label>
                                        <input type="text" name="links[{{ $index }}][title]" value="{{ old('links.'.$index.'.title', $link->title) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                               placeholder="Enter link title">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Type</label>
                                        <select name="links[{{ $index }}][link_type]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                            <option value="website" {{ old('links.'.$index.'.link_type', $link->link_type) == 'website' ? 'selected' : '' }}>Website</option>
                                            <option value="youtube" {{ old('links.'.$index.'.link_type', $link->link_type) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="drive" {{ old('links.'.$index.'.link_type', $link->link_type) == 'drive' ? 'selected' : '' }}>Google Drive</option>
                                            <option value="other" {{ old('links.'.$index.'.link_type', $link->link_type) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
                                        <input type="url" name="links[{{ $index }}][url]" value="{{ old('links.'.$index.'.url', $link->url) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                               placeholder="https://example.com">
                                    </div>
                                    @if(!$loop->first)
                                        <div class="md:col-span-3 flex justify-end">
                                            <button type="button" class="remove-link text-red-500 hover:text-red-700" data-link-id="{{ $link->id }}">
                                                <i class="fas fa-times-circle"></i> Remove
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="link-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-md mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Title</label>
                                        <input type="text" name="links[0][title]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                               placeholder="Enter link title">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Type</label>
                                        <select name="links[0][link_type]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                            <option value="website">Website</option>
                                            <option value="youtube">YouTube</option>
                                            <option value="drive">Google Drive</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
                                        <input type="url" name="links[0][url]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                               placeholder="https://example.com">
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-link" class="text-blue-500 hover:text-blue-700 font-medium">
                            <i class="fas fa-plus-circle mr-1"></i> Add Another Link
                        </button>
                    </div>

                    <!-- Existing Attachments -->
                    @if($resource->attachments->count() > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Existing Attachments</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($resource->attachments as $attachment)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h3 class="text-lg font-medium">{{ $attachment->title }}</h3>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            {{ ucfirst($attachment->file_type) }}
                        </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ \Illuminate\Support\Str::afterLast($attachment->path, '/') }}
                        </span>
                                            <div class="flex space-x-2">
                                                <a href="{{ asset('storage/' . $attachment->path) }}" download class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="text-red-600 hover:text-red-800 remove-attachment" data-attachment-id="{{ $attachment->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Attachments -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Add New Attachments</h4>
                        <div id="attachments-container">
                            <div class="attachment-item grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-md mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment Title</label>
                                    <input type="text" name="attachments[0][title]"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                           placeholder="Enter attachment title">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Type</label>
                                    <select name="attachments[0][file_type]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="document">Document</option>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                        <option value="audio">Audio</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File</label>
                                    <input type="file" name="attachments[0][file]"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-attachment" class="text-blue-500 hover:text-blue-700 font-medium">
                            <i class="fas fa-plus-circle mr-1"></i> Add Another Attachment
                        </button>
                    </div>

                    <!-- Hidden inputs for deleted items -->
                    <div id="deleted-links-container"></div>
                    <div id="deleted-attachments-container"></div>

                    <!-- Resource Statistics -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Resource Statistics</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Views</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $resource->views_count }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Downloads</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $resource->download_count }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Last Updated</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $resource->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between space-x-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <button type="button" id="deleteResource" class="px-4 py-2 border border-red-300 rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                            Delete Resource
                        </button>

                        <div class="flex space-x-4">
                            <a href="{{ route('teacher.resources.show', $resource->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                Update Resource
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 items-center justify-center hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 ease-in-out"></div>

        <!-- Modal panel -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-auto transform transition-all opacity-0 translate-y-4 duration-300 ease-in-out absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <div class="p-6">
                <div class="flex items-center justify-center mb-4 text-red-600 dark:text-red-500">
                    <div class="rounded-full bg-red-100 dark:bg-red-900/30 p-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>

                <h3 id="modal-title" class="text-lg font-medium text-center text-gray-900 dark:text-white mb-2">Delete Resource</h3>
                <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
                    Are you sure you want to delete this resource? This action cannot be undone.
                </p>

                <div class="flex justify-center space-x-4">
                    <button id="cancelDelete" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button id="confirmDelete" type="button" class="px-4 py-2 border border-transparent rounded-md font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 dark:bg-red-700 dark:hover:bg-red-800">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subject_id');
            const chapterSelect = document.getElementById('chapter_id');
            const subChapterSelect = document.getElementById('sub_chapter_id');

            // Load chapters when subject changes
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
                subChapterSelect.innerHTML = '<option value="">Select Sub Chapter</option>';

                if (subjectId) {
                    fetch(`/api/subjects/${subjectId}/chapters`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(chapter => {
                                const option = document.createElement('option');
                                option.value = chapter.id;
                                option.textContent = chapter.name;
                                chapterSelect.appendChild(option);
                            });
                        });
                }
            });

            // Load sub-chapters when chapter changes
            chapterSelect.addEventListener('change', function() {
                const chapterId = this.value;
                subChapterSelect.innerHTML = '<option value="">Select Sub Chapter</option>';

                if (chapterId) {
                    fetch(`/api/chapters/${chapterId}/sub-chapters`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subChapter => {
                                const option = document.createElement('option');
                                option.value = subChapter.id;
                                option.textContent = subChapter.name;
                                subChapterSelect.appendChild(option);
                            });
                        });
                }
            });

            // Add more links
            let linkCounter = {{ $resource->links->count() > 0 ? $resource->links->count() : 1 }};
            document.getElementById('add-link').addEventListener('click', function() {
                const linksContainer = document.getElementById('links-container');
                const newLinkItem = document.createElement('div');
                newLinkItem.className = 'link-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-md mb-4';
                newLinkItem.innerHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Title</label>
                        <input type="text" name="links[${linkCounter}][title]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                               placeholder="Enter link title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Type</label>
                        <select name="links[${linkCounter}][link_type]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="website">Website</option>
                            <option value="youtube">YouTube</option>
                            <option value="drive">Google Drive</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
                        <input type="url" name="links[${linkCounter}][url]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                               placeholder="https://example.com">
                    </div>
                    <div class="md:col-span-3 flex justify-end">
                        <button type="button" class="remove-link text-red-500 hover:text-red-700">
                            <i class="fas fa-times-circle"></i> Remove
                        </button>
                    </div>
                `;
                linksContainer.appendChild(newLinkItem);
                linkCounter++;

                // Add event listener to the new remove button
                newLinkItem.querySelector('.remove-link').addEventListener('click', function() {
                    newLinkItem.remove();
                });
            });

            // Remove existing links
            document.querySelectorAll('.remove-link[data-link-id]').forEach(button => {
                button.addEventListener('click', function() {
                    const linkId = this.getAttribute('data-link-id');
                    const deletedLinksContainer = document.getElementById('deleted-links-container');

                    // Add hidden input to track deleted link
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_links[]';
                    hiddenInput.value = linkId;
                    deletedLinksContainer.appendChild(hiddenInput);

                    // Remove the link item from the UI
                    this.closest('.link-item').remove();
                });
            });

            // Add more attachments
            let attachmentCounter = 1;
            document.getElementById('add-attachment').addEventListener('click', function() {
                const attachmentsContainer = document.getElementById('attachments-container');
                const newAttachmentItem = document.createElement('div');
                newAttachmentItem.className = 'attachment-item grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-md mb-4';
                newAttachmentItem.innerHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment Title</label>
                        <input type="text" name="attachments[${attachmentCounter}][title]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                               placeholder="Enter attachment title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Type</label>
                        <select name="attachments[${attachmentCounter}][file_type]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="document">Document</option>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File</label>
                        <input type="file" name="attachments[${attachmentCounter}][file]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="button" class="remove-attachment text-red-500 hover:text-red-700">
                            <i class="fas fa-times-circle"></i> Remove
                        </button>
                    </div>
                `;
                attachmentsContainer.appendChild(newAttachmentItem);
                attachmentCounter++;

                // Add event listener to the new remove button
                newAttachmentItem.querySelector('.remove-attachment').addEventListener('click', function() {
                    newAttachmentItem.remove();
                });
            });

            // Remove existing attachments
            document.querySelectorAll('.remove-attachment[data-attachment-id]').forEach(button => {
                button.addEventListener('click', function() {
                    const attachmentId = this.getAttribute('data-attachment-id');
                    const deletedAttachmentsContainer = document.getElementById('deleted-attachments-container');

                    // Add hidden input to track deleted attachment
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_attachments[]';
                    hiddenInput.value = attachmentId;
                    deletedAttachmentsContainer.appendChild(hiddenInput);

                    // Remove the attachment item from the UI
                    this.closest('.border').remove();
                });
            });

            // Delete confirmation modal handling
            const deleteModal = document.getElementById('deleteConfirmModal');
            const deleteBtn = document.getElementById('deleteResource');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            const modalContent = deleteModal.querySelector('.bg-white');

            // Function to show modal with animation
            function showModal() {
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');

                // Trigger animation after a small delay for the browser to recognize the element
                setTimeout(() => {
                    modalContent.classList.remove('opacity-0', 'translate-y-4');
                    modalContent.classList.add('opacity-100', 'translate-y-0');
                }, 10);

                // Focus the cancel button by default (for accessibility)
                cancelBtn.focus();
            }

            // Function to hide modal with animation
            function hideModal() {
                modalContent.classList.remove('opacity-100', 'translate-y-0');
                modalContent.classList.add('opacity-0', 'translate-y-4');

                // Wait for animation to complete before hiding
                setTimeout(() => {
                    deleteModal.classList.remove('flex');
                    deleteModal.classList.add('hidden');
                }, 300);
            }

            // Show delete confirmation modal when delete button is clicked
            deleteBtn.addEventListener('click', showModal);

            // Hide modal when cancel button is clicked
            cancelBtn.addEventListener('click', hideModal);

            // Close modal when clicking outside
            deleteModal.addEventListener('click', function(e) {
                // Only close if the backdrop was clicked (not the modal itself)
                if (e.target === deleteModal) {
                    hideModal();
                }
            });

            // Handle keyboard events
            document.addEventListener('keydown', function(e) {
                // Close modal on Escape key
                if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                    hideModal();
                }
            });

            // Handle delete confirmation
            confirmBtn.addEventListener('click', function() {
                // Create and submit the delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('teacher.resources.destroy', $resource->id) }}';
                form.style.display = 'none';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);

                // Add a subtle loading effect to button when form is submitted
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...';
                confirmBtn.disabled = true;

                // Submit the form after a short delay to show the loading state
                setTimeout(() => {
                    form.submit();
                }, 300);
            });
        });
    </script>
@endsection
