<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Admin;
use Livewire\Component;

class AdminTable extends Component
{
    public $activeTab = 'all';
    public $search = '';
    public $pendingApproval = 0;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setApproved($id)
    {
        Admin::where('id', $id)->update(['is_approved' => 1]);
        session()->flash('message', 'Admin approved successfully!');

    }


    public function filterSearch()
    {

    }

    public function render()
    {
        $this->pendingApproval = Admin::where('is_approved', 0)->count();

        $query = Admin::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->activeTab) {
            case 'approved':
                $query->where('is_approved', 1);
                break;
            case 'pending':
                $query->where('is_approved', 0);
                break;
        }

        $admins = $query->get();

        return view('livewire.super-admin.admin-table', [
            'admins' => $admins,
            'pendingApproval' => $this->pendingApproval
        ]);
    }



}
