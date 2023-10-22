<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\HomeOwner;
use App\Models\HomeOwnerBlockLot;
use App\Models\Lot;
use App\Models\Rfid;
use Livewire\Component;

class HomeownerDelete extends Component
{
    public $modelId;
    public $modelKey;

    /**
     * Function to delete the Home Owner
     *
     * @return void
     */
    public function delete()
    {
        // delete the home owner
        $homeowner = HomeOwner::find($this->modelId);
        $homeowner->delete();

        // set the lot as 'available'
        $this->unassignLots();

        // archive the assigned 'RFID'
        $this->archiveRfid();

        // send an event for notification
        $this->emit('show.dialog', [
            'icon' => 'info',
            'title' => 'Delete Success',
            'message' => 'Home Owner has been successfully deleted!!',
            'redirect' => route('homeowners.list')
        ]);
    }

    /**
     * Update all assigned lots
     *
     * @return void
     */
    protected function unassignLots()
    {
        $blockLots = HomeOwnerBlockLot::where('home_owner_id', $this->modelId)->get();

        foreach ($blockLots as $blockLot) {
            $lot = $blockLot->lot;
            $updateLot = Lot::find($lot);
            $updateLot->update([
                'availability' => 'available'
            ]);

            $blockLot->delete();
        }
    }

    /**
     * Archive the assigned RFID of the deleted Home Owner
     *
     * @return void
     */
    public function archiveRfid()
    {
        // Check if RFID exists
        if ($rfid = Rfid::where('home_owner_id', $this->modelId)->first()) {
            // Archive the RFID
            $rfid->delete();
        }
    }

    /**
     * Initialize component data
     *
     * @return void
     */
    public function mount()
    {
        $this->modelKey = 'homeowner-' . $this->modelId;
    }

    /**
     * Define what blade file to render
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.homeowner.homeowner-delete');
    }
}
