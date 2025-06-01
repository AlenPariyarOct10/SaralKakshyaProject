<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardPendingApprovals extends Component
{
    public $search='';

    public function approveAdmin($id)
    {
        try{
            $admin = Admin::where('id', $id)->first();
            $admin->is_approved = 1;
            $admin->approved_by = Auth::guard('super_admin')->user()->id;
            $admin->approved_at = date('Y-m-d H:i:s');
            $admin->save();
            session()->flash('success', 'Admin approved successfully.');
        }catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function deleteAdmin($id)
    {
        try{
            $admin = Admin::destroy('id', $id);
            session()->flash('success', 'Admin deleted successfully.');
        }catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        if($this->search==''){
            $pendinglist = Admin::where('is_approved', 0)
                ->where('deleted_at', null)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }else{
            $pendinglist = Admin::where('is_approved', 0)
                ->where('deleted_at', null)
                ->where(function ($query) {
                    $query->where('fname', 'like', '%' . $this->search . '%')
                        ->orWhere('lname', 'like', '%' . $this->search . '%')
                        ->orWhere(DB::raw("CONCAT(fname, ' ', lname)"), 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('livewire.super-admin.dashboard-pending-approvals', compact('pendinglist'));
    }
}
