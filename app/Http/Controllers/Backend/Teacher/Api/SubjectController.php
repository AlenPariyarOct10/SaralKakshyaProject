<?php

namespace App\Http\Controllers\Backend\Teacher\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getChapters($id)
    {
        $subject = Subject::with(['chapters' => function ($query) {
            $query->whereNull('parent_id');
        }])->findOrFail($id);

        return response()->json($subject->chapters);
    }

    public function getSubChapters($id)
    {
        $subchapters = Chapter::where('parent_id', $id)->get();

        return response()->json($subchapters);
    }
}
