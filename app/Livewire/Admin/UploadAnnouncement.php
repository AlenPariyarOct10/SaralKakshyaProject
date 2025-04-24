<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Program;
use Livewire\Component;

class UploadAnnouncement extends Component
{
    public $selectedDepartment = '';
    public $programs = [];

    public function updateDepartment($value)
    {
        $this->selectedDepartment = $value;
        $this->programs = Program::where('department_id', $value)->get();
    }

    public function render()
    {
        $departments = Department::all();
        return view('livewire.admin.upload-announcement', compact('departments'));
    }
}
