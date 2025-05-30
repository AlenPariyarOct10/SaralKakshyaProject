<?php

namespace App\Livewire\Admin;

use App\Models\Program;
use Livewire\Component;

class EditSubjectForm extends Component
{
    public $programs;
    public $selectedProgram = null;
    public $selectedSemester = null;
    public $totalSemesters = null;
    public $currentSubject;



    public function mount($currentSubject)
    {
        $this->currentSubject = $currentSubject;
        $this->selectedSemester = $currentSubject->semester;
        $this->totalSemesters = $currentSubject->program->total_semesters ?? null;
        $this->programs = Program::select('id', 'name', 'total_semesters')->get();
    }

    public function updateSelectedProgram($programId)
    {
        if (empty($programId)) {
            $this->selectedProgram = null;
            $this->totalSemesters = null;
            $this->selectedSemester = null;
        } else {
            $this->selectedProgram = $programId;
            $program = Program::find($programId);
            $this->totalSemesters = $program?->total_semesters ?? null;
            $this->selectedSemester = null;
        }
    }

}

