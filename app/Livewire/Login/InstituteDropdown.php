<?php

namespace App\Livewire\Login;

use App\Models\Institute;
use Livewire\Component;

class InstituteDropdown extends Component
{
    public $search = '';           // Search term
    public $institutes = [];       // List of institutes
    public $selectedInstitute = null;  // Selected Institute ID

    // This function is triggered when the search term changes
    public function updatedSearch()
    {
        $this->institutes = Institute::where('name', 'like', '%' . $this->search . '%')
            ->take(10)  // Limit to 10 results
            ->get();
    }

    // This function is called when an institute is selected from the list
    public function selectInstitute($id)
    {
        $institute = Institute::find($id);
        if ($institute) {
            $this->selectedInstitute = $institute->id;  // Set the selected institute ID
            $this->search = $institute->name;           // Update the search input to selected institute name
            $this->institutes = [];                      // Clear the institute list
        }
    }

    // Render the component view
    public function render()
    {
        return view('livewire.login.institute-dropdown', [
            'institutes' => $this->institutes,
        ]);
    }

}
