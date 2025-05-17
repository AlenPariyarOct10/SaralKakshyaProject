<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    /**
     * Store chapters for a subject
     */
    public function store(Request $request, $subjectId)
    {
        $subject = Subject::findOrFail($subjectId);

        $request->validate([
            'chapters' => 'required|array',
            'chapters.*.title' => 'required|string|max:255',
            'chapters.*.chapter_number' => 'required|string|max:50',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.level' => 'required|integer|min:1|max:3',
            'chapters.*.parent_id' => 'nullable|exists:chapters,id',
        ]);

        try {
            \DB::transaction(function () use ($request, $subject) {
                // First delete removed chapters
                $existingChapterIds = collect($request->chapters)->pluck('id')->filter();
                Chapter::where('subject_id', $subject->id)
                    ->whereNotIn('id', $existingChapterIds)
                    ->delete();

                // Update or create chapters
                foreach ($request->chapters as $chapterData) {
                    $chapterData['subject_id'] = $subject->id;
                    $chapterData['slug'] = Str::slug($chapterData['title']);

                    if (isset($chapterData['id'])) {
                        Chapter::where('id', $chapterData['id'])
                            ->update($chapterData);
                    } else {
                        Chapter::create($chapterData);
                    }
                }

                // Reorder chapters based on their hierarchy
                $this->reorderChapters($subject);
            });

            return redirect()->back()
                ->with('success', 'Chapters saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error saving chapters: ' . $e->getMessage());
        }
    }

    /**
     * Reorder chapters to maintain proper hierarchy
     */
    protected function reorderChapters(Subject $subject)
    {
        $chapters = $subject->chapters()->orderBy('level')->orderBy('chapter_number')->get();
        $order = 1;

        foreach ($chapters as $chapter) {
            $chapter->update(['order' => $order++]);
        }
    }
}
