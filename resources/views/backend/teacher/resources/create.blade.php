@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Create Resource')

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <!-- Create Resource Form -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Add New Resource</h3>
                <a href="{{ route('teacher.resources.index') }}" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Resources
                </a>
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

            <form action="{{ route('teacher.resources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- Resource Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Resource Type</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="resource-type-card cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <input type="radio" name="type" id="type_book" value="book" class="hidden" required>
                                <label for="type_document" class="cursor-pointer">
                                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                        <i class="fa-solid fa-book text-2xl text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">E-Book & Notes</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDFs, Word docs, etc.</p>
                                </label>
                            </div>

                            <div class="resource-type-card cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <input type="radio" name="type" id="type_video" value="video" class="hidden">
                                <label for="type_video" class="cursor-pointer">
                                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                        <i class="fas fa-video text-2xl text-red-600 dark:text-red-400"></i>
                                    </div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Video</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tutorials, lectures, etc.</p>
                                </label>
                            </div>

                            <div class="resource-type-card cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <input type="radio" name="type" id="type_question" value="question" class="hidden">
                                <label for="type_audio" class="cursor-pointer">
                                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                        <i class="fa-solid fa-clipboard-question text-2xl text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Past Questions</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Exam question papers, etc.</p>
                                </label>
                            </div>

                            <div class="resource-type-card cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <input type="radio" name="type" id="type_link" value="link" class="hidden">
                                <label for="type_link" class="cursor-pointer">
                                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                        <i class="fas fa-link text-2xl text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Link</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">External resources, websites</p>
                                </label>
                            </div>

                            <div class="resource-type-card cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <input type="radio" name="type" id="type_other" value="other" class="hidden">
                                <label for="type_other" class="cursor-pointer">
                                    <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                        <i class="fas fa-folder text-2xl text-gray-600 dark:text-gray-400"></i>
                                    </div>
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Other</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Miscellaneous resources</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Basic Information</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resource Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                       placeholder="Enter resource title">
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
                                <select id="subject_id" name="subject_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                      placeholder="Enter resource description">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Chapter -->
                            <div>
                                <label for="chapter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chapter</label>
                                <select id="chapter_id" name="chapter_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="">Select Chapter</option>
                                    <!-- Will be populated via JavaScript -->
                                </select>
                            </div>

                            <!-- Sub Chapter -->
                            <div>
                                <label for="sub_chapter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub Chapter</label>
                                <select id="sub_chapter_id" name="sub_chapter_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="">Select Sub Chapter</option>
                                    <!-- Will be populated via JavaScript -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Links Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Links</h4>
                        <div id="links-container">
                            <div class="link-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-md mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Title</label>
                                    <input type="text" name="links[0][title]" value="{{ old('links.0.title') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                           placeholder="Enter link title">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Type</label>
                                    <select name="links[0][link_type]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="website" {{ old('links.0.link_type') == 'website' ? 'selected' : '' }}>Website</option>
                                        <option value="youtube" {{ old('links.0.link_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                        <option value="drive" {{ old('links.0.link_type') == 'drive' ? 'selected' : '' }}>Google Drive</option>
                                        <option value="other" {{ old('links.0.link_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
                                    <input type="url" name="links[0][url]" value="{{ old('links.0.url') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                           placeholder="https://example.com">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-link" class="text-blue-500 hover:text-blue-700 font-medium">
                            <i class="fas fa-plus-circle mr-1"></i> Add Another Link
                        </button>
                    </div>

                    <!-- Attachments Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Attachments</h4>
                        <div id="attachments-container">
                            <div class="attachment-item grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-md mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment Title</label>
                                    <input type="text" name="attachments[0][title]" value="{{ old('attachments.0.title') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                           placeholder="Enter attachment title">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Type</label>
                                    <select name="attachments[0][file_type]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="document" {{ old('attachments.0.file_type') == 'document' ? 'selected' : '' }}>Document</option>
                                        <option value="image" {{ old('attachments.0.file_type') == 'image' ? 'selected' : '' }}>Image</option>
                                        <option value="video" {{ old('attachments.0.file_type') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="audio" {{ old('attachments.0.file_type') == 'audio' ? 'selected' : '' }}>Audio</option>
                                        <option value="other" {{ old('attachments.0.file_type') == 'other' ? 'selected' : '' }}>Other</option>
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

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('teacher.resources.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Create Resource
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        // Resource type selection
        const resourceTypeCards = document.querySelectorAll('.resource-type-card');
        resourceTypeCards.forEach(card => {
            card.addEventListener('click', () => {
                // Remove active class from all cards
                resourceTypeCards.forEach(c => {
                    c.classList.remove('border-primary-500');
                    c.classList.add('border-gray-200', 'dark:border-gray-700');
                });

                // Add active class to selected card
                card.classList.remove('border-gray-200', 'dark:border-gray-700');
                card.classList.add('border-primary-500');

                // Check the radio button
                const radio = card.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Dynamic subject/chapter/subchapter selection
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
            let linkCounter = 1;
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
        });
    </script>
@endsection
