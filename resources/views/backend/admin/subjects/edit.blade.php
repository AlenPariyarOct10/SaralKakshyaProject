@php use Illuminate\Support\Facades\Auth; @endphp
@extends("backend.layout.admin-dashboard-layout")

@php
    $user = Auth::user();
@endphp

@section('username')
    {{$user->fname}} {{$user->lname}}
@endsection

@section('fname')
    {{$user->fname}}
@endsection
@section('lname')
    {{$user->lname}}
@endsection
@section('profile_picture')
    {{$user->profile_picture}}
@endsection

@push("styles")
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .btn-secondary {
                @apply px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .btn-danger {
                @apply px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .table-header {
                @apply px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider;
            }
            .table-cell {
                @apply px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200;
            }
            .badge {
                @apply px-2.5 py-1 text-xs font-medium rounded-full;
            }
            .dropdown-item {
                @apply block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Subject</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Edit an existing subject for your academic program</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.subjects.index') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Subjects
                </a>
            </div>
        </div>


        <!-- error message -->
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative error-message hidden" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <ul class="mt-2 list-disc list-inside text-sm" id="error-list">
            </ul>
        </div>
        @livewire('admin.edit-subject-form', compact('programs', 'currentSubjectEvaluation','currentSubject'))

        <!-- Chapters and Sub-chapters -->
        <div class="card mb-6">
            <form action="">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Chapters Structure</h2>

                <div id="chapters-container" class="space-y-4">
                    <!-- Chapter template will be added here -->
                </div>

                <div class="mt-4">
                    <button type="button" id="add-chapter" class="btn-secondary flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Chapter
                    </button>
                </div>
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Save Chapters
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        //Handling chapter and sub-chapter addition/removal
        $(document).ready(function () {

            // Chapters functionality
            let chapterCount = 0;

            $('#add-chapter').on('click', function() {
                addNewChapter();
            });

            function addNewChapter(parentId = null, level = 1, title = '', description = '', chapterNumber = '') {
                chapterCount++;
                const chapterId = `chapter_${chapterCount}`;
                const isSubchapter = parentId !== null;
                const indentClass = isSubchapter ? 'ml-8 border-l-2 border-gray-300 dark:border-gray-600 pl-4' : '';

                const chapterHtml = `
                    <div class="chapter-item ${indentClass} bg-gray-50 dark:bg-gray-700 p-4 rounded-lg" data-level="${level}" id="${chapterId}" data-chapter-id="${chapterCount}">
                        <input type="hidden" name="chapters[${chapterCount}][parent_id]" value="${parentId || ''}">
                        <input type="hidden" name="chapters[${chapterCount}][level]" value="${level}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                            <div>
                                <label class="form-label">Chapter Number</label>
                                <input type="text" name="chapters[${chapterCount}][chapter_number]"
                                       class="form-input" placeholder="e.g. ${chapterCount}.${level}" value="${chapterNumber}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">Chapter Title</label>
                                <input type="text" name="chapters[${chapterCount}][title]"
                                       class="form-input" placeholder="Chapter title" value="${title}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="chapters[${chapterCount}][description]"
                                      class="form-input" rows="2" placeholder="Chapter description">${description}</textarea>
                        </div>

                        <div class="flex justify-between">
                            <div>
                                <button type="button" class="btn-secondary add-subchapter" data-chapter-id="${chapterCount}">
                                    <i class="fas fa-level-down-alt mr-2"></i> Add Sub-chapter
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn-danger remove-chapter">
                                    <i class="fas fa-trash mr-2"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                if (isSubchapter) {
                    $(`#chapter_${parentId}`).after(chapterHtml);
                } else {
                    $('#chapters-container').append(chapterHtml);
                }
            }

            // Initialize with one empty chapter
            addNewChapter();

            // Event delegation for dynamically added buttons
            $(document).on('click', '.add-subchapter', function() {
                const parentChapterId = $(this).data('chapter-id');
                const parentLevel = $(this).closest('.chapter-item').data('level');

                if (parentLevel >= 3) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Maximum nesting level reached (3 levels max)'
                    });
                    return;
                }

                addNewChapter(parentChapterId, parseInt(parentLevel) + 1);
            });

            $(document).on('click', '.remove-chapter', function() {
                const chapterItem = $(this).closest('.chapter-item');
                const chapterId = chapterItem.data('chapter-id');

                // Remove all subchapters of this chapter
                $(`.chapter-item input[name^="chapters["][name$="[parent_id]"]`).each(function() {
                    if ($(this).val() == chapterId) {
                        $(this).closest('.chapter-item').remove();
                    }
                });

                chapterItem.remove();
            });

        });
        //-------------------------------------
        $(document).ready(function() {
            let evaluationFormatItems = document.getElementById("evaluation-formats");

            console.log("all", evaluationFormatItems.childElementCount);
                let evaluationCount = 1;
            if(evaluationFormatItems.childElementCount>0)
            {
                evaluationCount = evaluationFormatItems.childElementCount-1;
            }
            let max_internal_marks = 0;
            let total = 0;

            $('#add-evaluation-format').on('click', function() {
                evaluationCount++;

                const newEvaluationFormat = `
                    <div class="evaluation-format grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="criteria_${evaluationCount}" class="form-label">Criteria <span class="text-red-500">*</span></label>
                            <input type="text" id="criteria_${evaluationCount}" name="criteria[]" class="form-input" placeholder="e.g. Final Exam" required>
                        </div>
                        <div>
                            <label for="full_marks_${evaluationCount}" class="form-label">Full Marks <span class="text-red-500">*</span></label>
                            <input type="number" id="full_marks_${evaluationCount}" name="full_marks[]" class="form-input" min="1" placeholder="e.g. 100" required>
                        </div>
                        <div class="relative">
                            <label for="pass_marks_${evaluationCount}" class="form-label">Pass Marks <span class="text-red-500">*</span></label>
                            <input type="number" id="pass_marks_${evaluationCount}" name="pass_marks[]" class="form-input" min="1" placeholder="e.g. 40" required>
                        </div>
                        <div>
                            <label for="marks_weight_${evaluationCount}" class="form-label">Marks Weight <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <input type="number" id="marks_weight_${evaluationCount}" name="marks_weight[]" class="form-input marks-weight" min="1" placeholder="e.g. 40" required>
                               <button type="button" class="remove-evaluation ml-2 p-2 text-red-500 hover:text-red-700 focus:outline-none" title="Remove">
                                  <i class="fas fa-trash-alt"></i>
                               </button>
                           </div>

                        </div>
                    </div>
                `;

                $('#evaluation-formats').append(newEvaluationFormat);

                // Add event listener to the newly added remove button
                $('.remove-evaluation').last().on('click', function() {
                    $(this).closest('.evaluation-format').remove();
                });


            });

            function calculateTotalWeight() {
                total = 0;
                $(".marks-weight").each(function () {
                    const val = parseFloat($(this).val());
                    if (!isNaN(val)) {
                        total += val;
                    }
                });
                $("#total-weight").text(total);
            }

            $(document).ready(function () {
                calculateTotalWeight();

                $(document).on('input', '.marks-weight', function () {
                    calculateTotalWeight();
                });
            });

            $(document).ready(function () {
                calculateTotalWeight();

                $(document).on('input', '#max_internal_marks', function () {
                    if(max_internal_marks<total)
                    {
                        alert("hello");
                    }
                });
            });

            $('form').on('submit', function(e) {
                let isValid = true;
                let error = [];

                if ($('#subjectName').val().trim() === '') {
                    isValid = false;
                    error.push("Name field cannot be empty");
                }

                if ($('#subjectCode').val().trim() === '') {
                    error.push("Subject code field cannot be empty");
                    isValid = false;
                }

                if(max_internal_marks<total)
                {
                    error.push("Marks weight cannot exceed max internal marks");
                    isValid = false;
                }

                if (!isValid) {
                    $('.error-message').classList.remove("hidden")

                    error.forEach((item)=>{
                        $('#error-list').innerHTML += `<li>${item}</li>`;
                    });
                    e.preventDefault();
                }else{
                    $('.error-message').classList.add("hidden");
                }
            });
        });
    </script>
@endsection
