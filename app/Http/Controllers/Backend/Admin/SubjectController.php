<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Subject;
use App\Models\SubjectEvaluationFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allSubjects = Subject::where('created_by', Auth::id())->get();
        return view('backend.admin.subjects.index', compact('allSubjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allSubjects = Subject::all();
        $programs = Program::all();
        return view('backend.admin.subjects.create',compact('allSubjects', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'credit' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:10000',
            'program_id' => 'required|exists:programs,id',
            'semester' => 'required',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query->where('program_id', $request->program_id);
                }),
            ],
            'max_external_marks'=>'required|numeric|min:0|max:500',
            'max_internal_marks'=>'required|numeric|min:0|max:500',
            'status'=>'required',
            'criteria' => 'required|array',
            'full_marks' => 'required|array',
            'pass_marks' => 'required|array',
            'marks_weight' => 'required|array',
            'marks_weight.*' => 'required|numeric|min:0|max:500',
            'pass_marks.*' => 'required|numeric|min:0|max:500',
            'full_marks.*' => 'required|numeric|min:0|max:500',
        ]);
        // Validate and create subject
        $subject = Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'credit' => $request->credit,
            'description' => $request->description,
            'program_id' => $request->program_id,
            'semester' => $request->semester,
            'status' => 1, // or whatever you want
            'created_by' => auth()->id(), // if you want
            'max_internal_marks'=>$request->max_internal_marks,
            'max_external_marks'=>$request->max_external_marks,
        ]);

        // Insert evaluation formats
        foreach ($request->criteria as $index => $criteria) {
            SubjectEvaluationFormat::create([
                'subject_id' => $subject->id,
                'criteria' => $criteria,
                'full_marks' => $request->full_marks[$index],
                'pass_marks' => $request->pass_marks[$index],
                'marks_weight' => $request->marks_weight[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Subject and Evaluation formats added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $allSubjects = Subject::all();
        $programs = Program::all();

        $currentSubject = Subject::where('id', $id)->get();
        $currentSubjectEvaluation = SubjectEvaluationFormat::where('subject_id', $id)->get();
        return view('backend.admin.subjects.edit',compact('allSubjects', 'programs','currentSubjectEvaluation', 'currentSubject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
