
@extends("backend.layout.admin-dashboard-layout")


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
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200;
            }
            .btn-secondary {
                @apply px-6 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors duration-200;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .form-select {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white bg-white dark:bg-gray-700;
            }
            .form-checkbox {
                @apply h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700;
            }
        }
    </style>
@endpush

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileUpload = document.getElementById('file-upload');
            const fileList = document.getElementById('file-list');
            const dropZone = fileUpload.closest('.border-dashed');

            // Handle file selection via input
            fileUpload.addEventListener('change', handleFiles);

            // Handle drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-primary-500');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-primary-500');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-primary-500');

                if (e.dataTransfer.files.length) {
                    handleFileUpload(e.dataTransfer.files);
                }
            });

            function handleFiles(e) {
                const files = e.target.files;
                handleFileUpload(files);
            }

            function handleFileUpload(files) {
                for (let i = 0; i < files.length; i++) {
                    uploadFile(files[i]);
                }
            }

            function uploadFile(file) {
                // Create file item element
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md';

                // File info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center space-x-2';

                // File icon based on type
                const fileIcon = document.createElement('i');
                if (file.type.includes('pdf')) {
                    fileIcon.className = 'fas fa-file-pdf text-red-500';
                } else if (file.type.includes('doc')) {
                    fileIcon.className = 'fas fa-file-word text-blue-500';
                } else if (file.type.includes('image')) {
                    fileIcon.className = 'fas fa-file-image text-green-500';
                } else {
                    fileIcon.className = 'fas fa-file text-gray-500';
                }

                // File name and size
                const fileDetails = document.createElement('div');
                fileDetails.innerHTML = `
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${file.name}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">${formatFileSize(file.size)}</p>
        `;

                // Status container
                const statusContainer = document.createElement('div');
                statusContainer.className = 'flex items-center space-x-2';

                // Progress container
                const progressContainer = document.createElement('div');
                progressContainer.className = 'w-24 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700';

                // Progress bar
                const progressBar = document.createElement('div');
                progressBar.className = 'bg-primary-600 h-2.5 rounded-full';
                progressBar.style.width = '0%';

                // Status text
                const statusText = document.createElement('span');
                statusText.className = 'text-xs font-medium text-gray-500 dark:text-gray-400';
                statusText.textContent = 'Preparing...';

                // Append elements
                progressContainer.appendChild(progressBar);
                statusContainer.appendChild(progressContainer);
                statusContainer.appendChild(statusText);

                fileInfo.appendChild(fileIcon);
                fileInfo.appendChild(fileDetails);

                fileItem.appendChild(fileInfo);
                fileItem.appendChild(statusContainer);

                fileList.appendChild(fileItem);

                // Simulate file upload with progress
                simulateFileUpload(progressBar, statusText, fileItem);
            }

            function simulateFileUpload(progressBar, statusText, fileItem) {
                let progress = 0;
                statusText.textContent = 'Uploading...';

                const interval = setInterval(() => {
                    progress += Math.random() * 10;
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(interval);

                        // Upload complete
                        setTimeout(() => {
                            statusText.textContent = 'Uploaded';
                            statusText.className = 'text-xs font-medium text-green-500';

                            // Replace progress bar with success icon
                            const progressContainer = progressBar.parentElement;
                            progressContainer.innerHTML = '';

                            const successIcon = document.createElement('i');
                            successIcon.className = 'fas fa-check-circle text-green-500 text-lg';
                            progressContainer.appendChild(successIcon);

                            // Add remove button
                            const removeBtn = document.createElement('button');
                            removeBtn.className = 'ml-2 text-gray-400 hover:text-red-500 focus:outline-none';
                            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                            removeBtn.addEventListener('click', function() {
                                fileItem.remove();
                            });

                            progressContainer.appendChild(removeBtn);
                        }, 500);
                    }

                    progressBar.style.width = `${progress}%`;
                }, 200);

                // In a real implementation, you would use FormData and fetch/axios to upload the file
                // const formData = new FormData();
                // formData.append('file', file);
                //
                // fetch('/upload-endpoint', {
                //     method: 'POST',
                //     body: formData
                // })
                // .then(response => response.json())
                // .then(data => {
                //     // Handle successful upload
                // })
                // .catch(error => {
                //     // Handle error
                // });
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
@endsection

@section('content')
    <!-- Main Content Area -->
    @livewire('admin.upload-announcement')
@endsection
