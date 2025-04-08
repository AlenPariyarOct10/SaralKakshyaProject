<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Admin;
use Livewire\Component;

class AdminTable extends Component
{
    public $activeTab = 'all';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $allAdmins = Admin::all();
        $pendingAdmins = Admin::where('is_approved', '1')->get();
        $approvedAdmins = Admin::where('is_approved', '0')->get();

        return view('livewire.super-admin.admin-table', [
            'allAdmins' => $allAdmins,
            'pendingAdmins' => $pendingAdmins,
            'approvedAdmins' => $approvedAdmins,
        ]);
    }
}
