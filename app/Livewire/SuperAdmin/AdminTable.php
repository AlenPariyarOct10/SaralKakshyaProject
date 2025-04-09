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

    public function setDeleted($id)
    {
        Admin::destroy($id);
        session()->flash('message', 'Admin deleted successfully!');
    }


    public function filterSearch()
    {

    }

    public function render()
    {
        // Calculate the number of admins pending approval
        $this->pendingApproval = Admin::where('is_approved', 0)->count();

        // Base query for Admins
        $query = Admin::query();

        // Apply search filter if any search query is present
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply tab-specific filters
        switch ($this->activeTab) {
            case 'approved':
                $query->where('is_approved', 1);
                break;
            case 'pending':
                $query->where('is_approved', 0);
                break;
            case 'trashed': // Handle trashed admins logic here
                // Fetch only trashed admins (soft deleted)
                $admins = Admin::onlyTrashed()->get();
                break;
            default:
                // Default case fetches all admins
                $admins = $query->get();
                break;
        }

        // Fetch admins (if not in trashed tab)
        if ($this->activeTab !== 'trashed') {
            $admins = $query->get();
        }

        // Return view with admins and pendingApproval count
        return view('livewire.super-admin.admin-table', [
            'admins' => $admins,
            'pendingApproval' => $this->pendingApproval
        ]);
    }




}
