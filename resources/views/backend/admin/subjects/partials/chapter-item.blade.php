@php
    $indentClass = $level > 1 ? 'ml-8 border-l-2 border-gray-300 dark:border-gray-600 pl-4' : '';
@endphp

<div class="chapter-item {{ $indentClass }} bg-gray-50 dark:bg-gray-700 p-4 rounded-lg" data-level="{{ $level }}" data-chapter-id="{{ $chapter->id }}">
    <input type="hidden" name="chapters[{{ $chapter->id }}][id]" value="{{ $chapter->id }}">
    <input type="hidden" name="chapters[{{ $chapter->id }}][parent_id]" value="{{ $chapter->parent_id }}">
    <input type="hidden" name="chapters[{{ $chapter->id }}][level]" value="{{ $level }}">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
        <div>
            <label class="form-label">Chapter Number</label>
            <input type="text" name="chapters[{{ $chapter->id }}][chapter_number]"
                   class="form-input" value="{{ $chapter->chapter_number }}" required>
        </div>
        <div class="md:col-span-2">
            <label class="form-label">Chapter Title</label>
            <input type="text" name="chapters[{{ $chapter->id }}][title]"
                   class="form-input" value="{{ $chapter->title }}" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="chapters[{{ $chapter->id }}][description]"
                  class="form-input" rows="2">{{ $chapter->description }}</textarea>
    </div>

    <div class="flex justify-between">
        <div>
            @if($level < 3)
                <button type="button" class="btn-secondary add-subchapter" data-chapter-id="{{ $chapter->id }}">
                    <i class="fas fa-level-down-alt mr-2"></i> Add Sub-chapter
                </button>
            @endif
        </div>
        <div>
            <button type="button" class="btn-danger remove-chapter">
                <i class="fas fa-trash mr-2"></i> Remove
            </button>
        </div>
    </div>

    @foreach($chapter->children as $child)
        @include('backend.admin.subjects.partials.chapter-item', ['chapter' => $child, 'level' => $level + 1])
    @endforeach
</div>
