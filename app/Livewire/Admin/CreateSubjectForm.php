<?php

namespace App\Livewire\Admin;

use App\Models\Program;
use Livewire\Component;

class CreateSubjectForm extends Component
{
    public $programs;
    public $selectedProgram = null;
    public $selectedSemester = null;
    public $totalSemesters = null;

    public function mount()
    {
        $this->programs = Program::select('id', 'name', 'total_semesters')->get();
    }

    public function updateSelectedProgram($programId)
    {
        $this->selectedProgram = $programId;
        $this->totalSemesters = $programId ? Program::find($programId)->total_semesters : null;
        $this->selectedSemester = null;
    }

    public function render()
    {
        return view('livewire.admin.create-subject-form');
    }
}
