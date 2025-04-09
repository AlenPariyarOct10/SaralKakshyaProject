<!-- Main Content Area -->
<main class="scrollable-content p-4 md:p-6">

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative"
             role="alert">
            <span class="block sm:inline">{{session('message')}}</span>
        </div>
    @endif

    <!-- Attendance Management -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Testimonial</h3>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative">
                    <button data-mode="new" id="addTestimonialBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        Add New
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-max border-collapse divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col"
                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        User Name
                    </th>
                    <th scope="col"
                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Stars
                    </th>
                    <th scope="col"
                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col"
                        class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Designation
                    </th>
                    <th scope="col"
                        class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($allTestimonial as $row)
                    <tr class="text-sm">
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800 dark:text-white">{{$row->user_name}}</span>
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500 dark:text-gray-400">{{$row->stars}}</span>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            <span
                                class="block max-w-xs md:max-w-sm text-gray-500 dark:text-gray-400 break-words">{{$row->description}}</span>
                        </td>
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500 dark:text-gray-400">{{$row->designation}}</span>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                <button data-mode="edit" wire:click="setEditId({{$row->id}})" data-id="{{$row->id}}"
                                        class="editTestimonialBtn px-2 py-1 text-xs sm:text-sm bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                                    Edit
                                </button>
                                <button data-id="{{$row->id}}" wire:click="setDeleteId({{$row->id}})"
                                        class="deleteTestimonialBtn px-2 py-1 text-xs sm:text-sm bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 md:px-6 py-4 text-center text-gray-800 dark:text-white" colspan="5">
                            No Items Found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- Add Testimonial Modal -->
        <div id="addTestimonialModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="addModalOverlay"></div>
            <div
                class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Add Testimonial</h3>
                        <button id="closeAddModal"
                                class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                                aria-label="Close modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="{{route("admin.testimonial.store")}}" method="post" id="addTestimonialForm"
                          enctype="multipart/form-data">
                        @csrf
                        <!-- Profile Picture Upload -->
                        <div class="mb-6">
                            <label for="addProfilePicture" class="form-label">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                <div id="addProfilePreview"
                                     class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                    <span id="addPreviewPlaceholder" class="text-gray-400">No image</span>
                                    <img id="addImagePreview" class="hidden w-full h-full object-cover"
                                         alt="Profile preview">
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="addProfilePicture" name="profilePicture"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                           accept="image/*">
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF (Max. 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="addUserName" class="form-label">User Name</label>
                            <input type="text" id="addUserName" name="userName" class="form-input"
                                   placeholder="Enter user name" required>
                        </div>

                        <div class="mb-4">
                            <label for="addStars" class="form-label">Stars</label>
                            <select id="addStars" name="stars" class="form-input" required>
                                <option value="">Select Rating</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="addDesignation" class="form-label">Designation</label>
                            <input type="text" id="addDesignation" name="designation" class="form-input"
                                   placeholder="Enter designation" required>
                        </div>

                        <div class="mb-4">
                            <label for="addRank" class="form-label">Rank</label>
                            <input type="number" min="0" id="addRank" name="rank" class="form-input"
                                   placeholder="Enter rank" required>
                        </div>

                        <div class="mb-4">
                            <label for="addTestimonialStatus" class="form-label">Status</label>
                            <select id="addTestimonialStatus" name="testimonialStatus" class="form-input" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="addTestimonialDescription" class="form-label">Description</label>
                            <textarea id="addTestimonialDescription" name="testimonialDescription" class="form-input"
                                      rows="3" placeholder="Enter testimonial description" required></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" id="addCancelBtn" class="btn-secondary">Cancel</button>
                            <button type="submit" id="addSaveBtn" class="btn-primary">Save Testimonial</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($editIsVisible)
        <!-- Edit Testimonial Modal -->
        <div id="editTestimonialModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="editModalOverlay"></div>
            <div
                class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="editModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Edit
                            Testimonial</h3>
                        <button id="closeEditModal" wire:click="toggleEditModal()"
                                class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                                aria-label="Close modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="{{route('admin.testimonial.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editTestimonialId" name="testimonialId" value="{{$editingTestimonial->id}}">

                        <!-- Profile Picture Upload -->
                        <div class="mb-6">
                            <label for="editProfilePicture" class="form-label">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                @if(!asset('storage/'.$editingTestimonial->profile_picture))
                                    <div id="editProfilePreview"
                                         class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                        <span id="editPreviewPlaceholder" class="text-gray-400">No image</span>
                                        <img id="editImagePreview" class="hidden w-full h-full object-cover"
                                             alt="Profile preview">
                                    </div>
                                @endif
                                @if(asset('storage/'.$editingTestimonial->profile_picture))
                                    <div id="editProfilePreview"
                                         class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                        <img id="editImagePreview" class="w-full h-full object-cover" src="{{asset('storage/'.$editingTestimonial->profile_picture)}}"
                                             alt="Profile preview">
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <input type="file" id="editProfilePicture" name="profilePicture"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                           accept="image/*">
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF (Max. 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="editUserName" class="form-label">User Name</label>

                            <input type="text" id="editUserName" name="userName" value="{{$editingTestimonial->user_name}}" class="form-input"
                                   placeholder="Enter user name" required>
                        </div>

                        <div class="mb-4">
                            <label for="editStars" class="form-label">Stars</label>
                            <select id="editStars" name="stars" class="form-input" required>
                                <option value="">Select Rating</option>
                                @for($i=1;$i<=5;$i++)
                                    <option value="{{$i}}" {{($editingTestimonial->stars==$i)?'selected':''}}>{{$i}}</option>
                                @endfor

                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="editDesignation" class="form-label">Designation</label>
                            <input type="text" id="editDesignation" name="designation" class="form-input"
                                   placeholder="Enter designation"  value="{{$editingTestimonial->designation}}" required>
                        </div>

                        <div class="mb-4">
                            <label for="editRank" class="form-label">Rank</label>
                            <input type="number" min="0" id="editRank" name="rank"  value="{{$editingTestimonial->rank}}" class="form-input"
                                   placeholder="Enter rank" required>
                        </div>

                        <div class="mb-4">
                            <label for="editTestimonialStatus" class="form-label">Status</label>
                            <select id="editTestimonialStatus" name="testimonialStatus" class="form-input" required>
                                <option value="active" {{($editingTestimonial->status=='active')?'selected':''}}>Active</option>
                                <option value="inactive" {{($editingTestimonial->status=='inactive')?'selected':''}}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="editTestimonialDescription" class="form-label">Description</label>
                            <textarea id="editTestimonialDescription" name="testimonialDescription" class="form-input"
                                      rows="3" placeholder="Enter testimonial description" value="{{$editingTestimonial->description}}" required></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" wire:click="toggleEditModal()" id="editCancelBtn" class="btn-secondary">Cancel</button>
                            <button type="submit" id="editSaveBtn" class="btn-primary">Save Testimonial</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($deleteIsVisible)
        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteModalOverlay"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Confirm Deletion</h3>
                        <button wire:click="toggleDeleteModal()" id="closeDeleteModal"
                                class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                                aria-label="Close modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this testimonial?
                        This action cannot be undone.</p>

                    <div class="flex justify-end space-x-3">
                        <button id="cancelDeleteBtn" wire:click="toggleDeleteModal()" class="btn-secondary">Cancel
                        </button>
                        <button wire:click="deleteItem()" id="confirmDeleteBtn" class="btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</main>
