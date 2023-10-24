<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\HomeOwnerBlockLot;
use App\Models\HomeOwnerVehicle;
use App\Models\Lot;
use App\Models\Profile;
use App\Models\Rfid;
use App\Models\Visitor;
use App\Rules\NotFutureDate;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class HomeownerView extends Component
{
    protected $listeners = [
        'deleteProfile',
        'deleteBlockLot',
        'deleteVehicle',
        'selectLot',
        'unSelectLot'
    ];

    public $data;

    public $createForm = [
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'date_of_birth' => '',
        'gender' => 'male',
        'contact_no' => '',
        'notes' => '',
    ];
    public $updateForm = null;
    public $toView = null;

    public $createVehicleForm = [
        'plate_number' => '',
        'car_type' => ''
    ];
    public $updateVehicleForm;
    public $availableLBlockLots = [];
    public $blockLotForm = [];

    public $homeOwnerId;
    public $visitorForm;

    public function create()
    {
        // validate the form
        $this->validate([
            'createForm.first_name' => ['required', 'string', 'min:2', 'max:30'],
            'createForm.last_name' => ['required', 'string', 'min:2', 'max:30'],
            'createForm.middle_name' => ['nullable', 'string', 'min:2', 'max:30'],
            'createForm.date_of_birth' => ['required', 'date', new NotFutureDate],
            'createForm.contact_no' => ['nullable', 'regex:/^09\d{9}$/', Rule::unique('profiles', 'contact_no')],
            'createForm.notes' => ['nullable']
        ], [
            'createForm.contact_no.regex' => 'Contact number format is invalid, valid format is: 09123456789'
        ]);

        // create a new profile
        $profileData = array_merge($this->createForm, [
            'home_owner_id' => $this->data->id,
            'date_joined' => $this->data->created_at
        ]);
        Profile::create($profileData);

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'Profile has been successfully added!',
            'reload' => true
        ]);
    }

    public function deleteProfile($payload)
    {
        // delete the profile
        Profile::find($payload['id'])->delete();

        // emit an event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Delete Success',
            'message' => 'Profile has been successfully deleted!',
            'reload' => true
        ]);
    }

    public function deleteBlockLot($payload)
    {
        $blockLot = HomeOwnerBlockLot::find($payload);
        $lotId = $blockLot->lot;

        $blockLot->delete();
        Lot::find($lotId)->update([
            'availability' => 'available'
        ]);

        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Unassign Success',
            'message' => 'Lot has been successfully unassigned!',
            'reload' => true
        ]);
    }

    // Set the profile to update
    public function setUpdate($id)
    {
        $this->updateForm = Profile::find($id)
            ->only(['id', 'first_name', 'last_name', 'middle_name', 'date_of_birth', 'contact_no', 'notes', 'gender']);

        // event an event to show update modal
        $this->emit('show.profile-update');
    }

    // update a profile
    public function update()
    {
        // validate the update form
        $this->validate([
            'updateForm.first_name' => ['required', 'string', 'min:2', 'max:30'],
            'updateForm.last_name' => ['required', 'string', 'min:2', 'max:30'],
            'updateForm.middle_name' => ['nullable', 'string', 'min:2', 'max:30'],
            'updateForm.date_of_birth' => ['required', 'date', new NotFutureDate],
            'updateForm.gender' => ['required'],
            'updateForm.contact_no' => [
                'nullable',
                'regex:/^09\d{9}$/',
                Rule::unique('profiles', 'contact_no')->ignore($this->updateForm['id'])
            ],
            'updateForm.notes' => ['nullable']
        ], [
            'updateForm.contact_no.regex' => 'Contact number format is invalid, valid format is: 09123456789'
        ]);

        // update the profile
        Profile::find($this->updateForm['id'])->update($this->updateForm);

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Profile has been successfully updated!',
            'reload' => true
        ]);
    }

    // set the profile to view
    public function setView($id)
    {
        $this->toView = Profile::find($id);

        // event an event to show update modal
        $this->emit('show.view-modal');
    }

    public function createVehicle()
    {
        // validate the form
        $this->validate([
            'createVehicleForm.plate_number' => ['required', Rule::unique('home_owner_vehicles', 'plate_number')],
            'createVehicleForm.car_type' => ['required'],
        ]);

        // create new vehicle
        $newVehicle = HomeOwnerVehicle::create([
            'home_owner_id' => $this->data->id,
            'plate_number' => $this->createVehicleForm['plate_number'],
            'car_type' => $this->createVehicleForm['car_type']
        ]);

        if ($newVehicle) {
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Register Success',
                'message' => 'New vehicle has been successfully registered!',
                'reload' => true
            ]);
        }
    }

    public function deleteVehicle($payload)
    {
        HomeOwnerVehicle::find($payload)->delete();

        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Delete Success',
            'message' => 'Hehicle has been successfully deleted!',
            'reload' => true
        ]);
    }

    public function prepareUpdateVehicle($payload)
    {
        $vehicle = HomeOwnerVehicle::with('rfid')
            ->find($payload)
            ->toArray();
        $this->updateVehicleForm = [
            'id' => data_get($vehicle, 'id'),
            'plate_number' => data_get($vehicle, 'plate_number'),
            'car_type' => data_get($vehicle, 'car_type'),
            'rfid_id' => data_get($vehicle, 'rfid.id'),
            'rfid' => data_get($vehicle, 'rfid.rfid'),
        ];
        
        $this->emit('update.vehicle-prepare');
    }

    public function updateVehicle()
    {
        $this->validate([
            'updateVehicleForm.plate_number' => [
                'required',
                Rule::unique('home_owner_vehicles', 'plate_number')->ignore($this->updateVehicleForm['id'], 'id')
            ],
            'updateVehicleForm.car_type' => ['required'],
            'updateVehicleForm.rfid' => [
                'sometimes',
                'nullable',
                Rule::unique('rfids', 'rfid')->ignore($this->updateVehicleForm['rfid_id'], 'id'),
            ],
        ]);

        $updateVehicle = HomeOwnerVehicle::find($this->updateVehicleForm['id']);
        $updateVehicle->update(
            Arr::only($this->updateVehicleForm, ['plate_number', 'car_type'])
        );

        // process update or delete rfid
        $this->processRfid();

        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Vehicle has been successfully updated!',
            'reload' => true
        ]);
    }

    public function processRfid()
    {
        $rfid_id = data_get($this->updateVehicleForm, 'rfid_id');
        $rfid = data_get($this->updateVehicleForm, 'rfid');

        $currentRfid = Rfid::find($rfid_id);
        if (empty($rfid)) {
            $currentRfid->forceDelete();
        } else {
            if ($currentRfid) {
                $currentRfid->update(compact('rfid'));
            } else {
                Rfid::create([
                    'vehicle_id' => data_get($this->updateVehicleForm, 'id'),
                    'rfid' => $rfid
                ]);
            }
        }
    }

    public function selectLot($id)
    {
        $this->blockLotForm[] = $id;
    }

    public function unSelectLot($id)
    {
        // Remove the value 'block' from the array
        $key = array_search($id, (array) $this->blockLotForm);
        if ($key !== false) {
            unset($this->blockLotForm[$key]);
        }

        // Re-index the array
        $this->blockLotForm = array_values((array) $this->blockLotForm);
    }

    public function addBlockLot()
    {
        $blockLots = (array) $this->blockLotForm;

        foreach ($blockLots as $blockLot) {
            $lot = Lot::with('block')->find($blockLot);
            $block = $lot->block->id;

            HomeOwnerBlockLot::create([
                'home_owner_id' => $this->data->id,
                'block' => $block,
                'lot' => $blockLot
            ]);

            Lot::find($blockLot)->update([
                'availability' => 'unavailable'
            ]);
        }

        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Assignment Success',
            'message' => 'Blot & Lot has been successfully assigned!',
            'reload' => true
        ]);
    }

    // Cretae a new Visitor QR Code
    public function createVisitorQr()
    {
        // validate the form
        $this->validate([
            'visitorForm.last_name' => ['required'],
            'visitorForm.first_name' => ['required'],
        ]);

        // crate the visitor data
        $this->visitorForm['token'] = sprintf('%s_%s_%s', $this->homeOwnerId, time(), Str::random(4));
        Visitor::create($this->visitorForm);

        // reset the form
        $this->visitorForm['token'] = '';
        $this->visitorForm['last_name'] = '';
        $this->visitorForm['first_name'] = '';

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'Visitor data create. You may now generate a QR code!',
            'reload' => true
        ]);
    }

    // generate and download qr code
    public function generateQrCode($id)
    {
        $route = route('download.qr', ['id' => $id], false);
        return redirect($route);
    }

    public function mount($id)
    {
        $this->homeOwnerId = $id;
        $this->data = HomeOwner::with([
            'profiles',
            'blockLots',
            'blockLots.block',
            'blockLots.lot',
            'vehicles',
            'vehicles.rfid',
            'visitors'
        ])->findOrFail($id);

        foreach (Block::all() as $block) {
            $lots = Lot::where('block_id', $block->id)
                ->where('availability', 'available')
                ->pluck('id', 'lot')->toArray();

            if (count($lots) > 0) {
                $this->availableLBlockLots[$block->block] = $lots;
            }
        }

        $this->visitorForm = [
            'home_owner_id' => $id,
            'last_name' => '',
            'first_name' => ''
        ];
    }

    public function render()
    {
        return view('livewire.Homeowner.homeowner-view')
            ->extends('layouts.admin')
            ->section('content');
    }
}
