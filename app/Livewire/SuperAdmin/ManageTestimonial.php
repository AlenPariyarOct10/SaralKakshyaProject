<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Testimonial;
use Livewire\Component;

class ManageTestimonial extends Component
{
    public $allTestimonials;
    public $testimonialId = null;
    public $editingTestimonial = null;


    public $deleteIsVisible=false;
    public $editIsVisible=false;
    public $addNewIsVisible=false;

    public function setDeleteId($id)
    {
        $this->testimonialId = $id;
        $this->toggleDeleteModal();
    }

    public function setEditId($id)
    {
        $this->testimonialId = $id;
        $this->toggleEditModal();

        $this->editingTestimonial = Testimonial::where('id', $id)->first();
    }

    public function toggleAddNewModal()
    {
        $this->addNewIsVisible=!$this->addNewIsVisible;
    }

    public function toggleDeleteModal()
    {
        $this->deleteIsVisible=!$this->deleteIsVisible;
        if($this->deleteIsVisible==false)
        {
            $this->testimonialId = null;
        }
    }
   public function toggleEditModal()
    {
        $this->editIsVisible=!$this->editIsVisible;
        if($this->deleteIsVisible==false)
        {
            $this->testimonialId = null;
        }
    }

    public function deleteItem()
    {
        $result = Testimonial::destroy($this->testimonialId);
        if($result)
        {
            session()->flash('message', 'Testimonial has been deleted.');
        }else{

            session()->flash('message', 'failed Testimonial has been deleted.');
        }

        $this->toggleDeleteModal();
    }


    public function render()
    {
        $allTestimonial = Testimonial::all();
        return view('livewire.super-admin.manage-testimonial',
            [
                'allTestimonial'=>$allTestimonial ,
                'testimonialId'=>$this->testimonialId,
                'deleteIsVisible'=>$this->deleteIsVisible,
                'editIsVisible'=>$this->editIsVisible,
                'editingTestimonial'=>$this->editingTestimonial
            ]);
    }
}
