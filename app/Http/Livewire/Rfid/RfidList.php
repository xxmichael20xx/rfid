<?php

namespace App\Http\Livewire\Rfid;

use App\Models\HomeOwner;
use App\Models\Rfid;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RfidList extends Component
{
    public $rfids;
    public $unassignedHomeOwners;

    protected $listeners = ['deleteRfid', 'setRfid'];

    public $rfidForm = [
        'home_owner_id' => '',
        'rfid' => ''
    ];

    public function rules()
    {
        return [
            'rfidForm.home_owner_id' => ['required'],
            'rfidForm.rfid' => ['required', Rule::unique('rfids', 'rfid')]
        ];
    }

    public function create()
    {
        // validate the rfid form
        $this->validate();

        // assign the rfid to a home owner
        Rfid::create($this->rfidForm);

        // emit the success dialog
        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Assign Success',
            'message' => 'RFID has been successfully assigned to a home owner!',
            'reload' => true
        ]);
    }

    public function deleteRfid($id)
    {
        $rfid = Rfid::find($id)->first();

        if (! $rfid) {
            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'RFID data not found!',
            ]);
        } else {
            $rfid->delete();

            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Delete success',
                'message' => 'RFID has been deleted!',
                'reload' => true
            ]);
        }
    }

    public function setRfid($id)
    {
        $this->rfidForm['rfid'] = $id;
    }

    public function mount()
    {
        // get the list of Rfids
        $this->rfids = Rfid::with(['homeOwner'])->get();

        // get home owners that doesn't have a rfid yet
        $homeOwners = HomeOwner::all()->pluck('id')->toArray();
        $rfids = Rfid::all()->pluck('home_owner_id')->toArray();

        // Find the home_owner_ids that do not exist in $rfids
        $homeOwnersWithoutRfid = array_diff($homeOwners, $rfids);

        // Fetch the corresponding HomeOwner models
        $this->unassignedHomeOwners = HomeOwner::whereIn('id', $homeOwnersWithoutRfid)->get();
    }

    public function render()
    {
        return view('livewire.rfid.rfid-list')
            ->extends('layouts.admin')
            ->section('content');
    }
}
