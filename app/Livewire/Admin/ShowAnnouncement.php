<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use App\Models\Department;
use App\Models\Institute;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAnnouncement extends Component
{
    use WithPagination;
    public function render()
    {
        $institute = Institute::where('created_by', Auth::id())->first();

        $pinnedAnnouncements = Announcement::where('institute_id', $institute->id)
            ->where('pinned', true)
            ->orderBy('created_at', 'DESC')->get();

        $allDepartments = Department::where('institute_id', $institute->id)->get();

        $allAnnouncements = Announcement::where('institute_id', $institute->id)
            ->where('pinned', false)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        return view('livewire.admin.show-announcement', ['allDepartments'=>$allDepartments,'allAnnouncements' => $allAnnouncements, 'pinnedAnnouncements' => $pinnedAnnouncements]);
    }
}
