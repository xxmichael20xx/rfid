<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\HomeOwner;
use App\Models\Lot;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class HomeownerDelete extends Component
{
    public $modelId;
    public $modelKey;

    /**
     * Function to delete the Home Owner
     */
    public function delete()
    {
        // delete the home owner
        $homeowner = HomeOwner::find($this->modelId);
        $homeowner->delete();

        // set the lot as 'available'
        Lot::find($homeowner->lot)->update([
            'availability' => 'available'
        ]);

        // send an event for notification
        $this->emit('show.dialog', [
            'icon' => 'info',
            'title' => 'Delete Success',
            'message' => 'Home Owner has been successfully deleted!!',
            'redirect' => route('homeowners.list')
        ]);
    }

    /**
     * Mount function to initialize dynamic data
     */
    public function mount()
    {
        $this->modelKey = 'homeowner-' . $this->modelId;
    }

    /**
     * Function to render the blade file
     */
    public function render()
    {
        return view('livewire.homeowner.homeowner-delete');
    }
}
