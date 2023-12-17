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
use Illuminate\Support\Facades\Config;
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
        'unSelectLot',
    ];

    public $data;

    public $createForm = [
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'date_of_birth' => '',
        'gender' => 'male',
        'contact_no' => '',
        'relation' => '',
    ];
    public $updateForm = null;
    public $toView = null;

    public $createVehicleForm;
    public $updateVehicleForm;
    public $availableLBlockLots = [];
    public $blockLotForm = [];

    public $homeOwnerId;
    public $visitorForm;

    public $carTypes;
    public $carNames;

    public $defaultCarType;
    public $defaultCarName;

    public $homeVehicles;
    public $searchVehicle;

    public function create()
    {
        // validate the form
        $this->validate([
            'createForm.first_name' => ['required', 'string', 'min:2', 'max:30'],
            'createForm.last_name' => ['required', 'string', 'min:2', 'max:30'],
            'createForm.middle_name' => ['nullable', 'string', 'min:2', 'max:30'],
            'createForm.date_of_birth' => ['required', 'date', new NotFutureDate],
            'createForm.contact_no' => [
                'nullable',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
                Rule::unique('profiles', 'contact_no')
            ],
            'createForm.relation' => ['required']
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
            ->only(['id', 'first_name', 'last_name', 'middle_name', 'date_of_birth', 'contact_no', 'relation', 'gender']);

        // event an event to show update modal
        $this->emit('show.profile-update', ['relation' => $this->updateForm['relation']]);
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
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
                Rule::unique('profiles', 'contact_no')->ignore($this->updateForm['id'])
            ],
            'updateForm.relation' => ['required']
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
            'createVehicleForm.car_name' => ['required'],
        ]);

        // create new vehicle
        $newVehicle = HomeOwnerVehicle::create([
            'home_owner_id' => $this->data->id,
            'plate_number' => $this->createVehicleForm['plate_number'],
            'car_type' => $this->createVehicleForm['car_type'],
            'car_name' => $this->createVehicleForm['car_name'],
        ]);

        if ($newVehicle) {
            $this->createVehicleForm = [
                'plate_number' => '',
                'car_type' => $this->defaultCarType,
                'car_name' => $this->defaultCarName
            ];

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
            'message' => 'Vehicle has been successfully deleted!',
            'reload' => true
        ]);
    }

    public function prepareUpdateVehicle($payload)
    {
        $vehicle = HomeOwnerVehicle::with('rfid')
            ->find($payload);

        $this->updateVehicleForm = [
            'id' => $vehicle->id,
            'plate_number' => $vehicle->plate_number,
            'car_type_u' => $vehicle->car_type,
            'car_name_u' => $vehicle->car_name
        ];

        // set the cars data from config files
        // $this->carTypes = Config::get('cars.car_types');
        // $this->carNames = data_get($this->carTypes, 'SUV');

        $carTypeRecords = HomeOwnerVehicle::withTrashed()->pluck('car_type')->toArray();
        $carTypeKeys = array_keys(Config::get('cars.car_types'));
        $this->carTypes = array_values(array_unique(array_merge($carTypeKeys, $carTypeRecords)));

        $carNameRecords = HomeOwnerVehicle::withTrashed()->where('car_type', $vehicle->car_type)->pluck('car_name')->toArray();
        $carNames = data_get(Config::get('cars.car_types'), $vehicle->car_type, []);
        $this->carNames = array_values(array_unique(array_merge($carNames, $carNameRecords)));

        $this->emit('update.vehicle-prepare', [
            'carType' => $vehicle->car_type,
            'carName' => $vehicle->car_name,
            'carTypes' => $this->carTypes,
            'carNames' => $this->carNames
        ]);
    }

    public function updateCarNameOnUpdate($value)
    {
        $this->updateVehicleForm['car_name_u'] = $value;
    }

    public function updateVehicle()
    {
        $this->validate([
            'updateVehicleForm.plate_number' => [
                'required',
                Rule::unique('home_owner_vehicles', 'plate_number')->ignore($this->updateVehicleForm['id'], 'id')
            ],
            'updateVehicleForm.car_type_u' => ['required'],
            'updateVehicleForm.car_type_u' => ['required']
        ]);

        $updateVehicleRawData = [
            'plate_number' => $this->updateVehicleForm['plate_number'],
            'car_type' => $this->updateVehicleForm['car_type_u'],
            'car_name' => $this->updateVehicleForm['car_name_u'],
        ];
        $updateVehicle = HomeOwnerVehicle::find($this->updateVehicleForm['id']);
        $updateVehicle->update($updateVehicleRawData);

        // process update or delete rfid
        // $this->processRfid();

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
            if ($currentRfid) {
                $currentRfid->forceDelete();
            }
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
            'message' => 'Block & Lot has been successfully assigned!',
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

    /**
     * Update the car names based on
     * selected car type.
     */
    public function createChangeCarType($type, $value)
    {
        $carNameRecords = HomeOwnerVehicle::withTrashed()->where('car_type', $value)->pluck('car_name')->toArray();
        $carNames = data_get(Config::get('cars.car_types'), $value, []);
        $this->carNames = array_values(array_unique(array_merge($carNames, $carNameRecords)));

        if ($type == 'create') {
            $this->createVehicleForm['car_type'] = $value;
            $this->createVehicleForm['car_name'] = data_get($this->carNames, '0');
            $this->emit('create.updated-car-names', $this->carNames);
        } else {
            $this->updateVehicleForm['car_type_u'] = $value;
            $this->updateVehicleForm['car_name_u'] = data_get($this->carNames, '0');
            $this->emit('update.updated-car-names', $this->carNames);
        }
    }

    /**
     * Update the value of the car name
     */
    public function createChangeCarName($type, $value)
    {
        $this->createVehicleForm['car_name'] = $value;
    }

    /**
     * Set the defaults of the car types and names
     */
    public function setDefaults()
    {
        $carTypeRecords = HomeOwnerVehicle::withTrashed()->pluck('car_type')->toArray();
        $carTypeKeys = array_keys(Config::get('cars.car_types'));
        $this->carTypes = array_values(array_unique(array_merge($carTypeKeys, $carTypeRecords)));

        $carNameRecords = HomeOwnerVehicle::withTrashed()->pluck('car_name')->toArray();
        $carNames = data_get(Config::get('cars.car_types'), 'SUV');
        $this->carNames = array_values(array_unique(array_merge($carNames, $carNameRecords)));
    }

    public function clearSearchVehicle()
    {
        return redirect()->route('homeowners.view', ['id' => $this->data->id]);
    }

    public function setNewRelationValue($value)
    {
        $this->createForm['relation'] = $value;
    }

    public function setUpdateRelationValue($value)
    {
        $this->updateForm['relation'] = $value;
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

        $this->homeVehicles = $this->data->vehicles;

        if ($this->searchVehicle = request()->input('vehicle-search')) {
            $searchKeyword = '%'. $this->searchVehicle .'%';
            $this->homeVehicles = HomeOwnerVehicle::where('home_owner_id', $this->data->id)
                ->where('plate_number', 'LIKE', $searchKeyword)
                ->orWhere('car_type', 'LIKE', $searchKeyword)
                ->orWhere('car_name', 'LIKE', $searchKeyword)
                ->orWhereHas('rfid', function($query) use ($searchKeyword) {
                    $query->where('rfid', 'LIKE', $searchKeyword);
                })
                ->get();
        }

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

        // get car types and names from database
        $this->setDefaults();

        // set the create vehicle form
        $this->createVehicleForm = [
            'plate_number' => '',
            'car_type' => 'SUV',
            'car_name' => data_get($this->carNames, '0')
        ];

        // set default car type and name
        $this->defaultCarType = 'SUV';
        $this->defaultCarName = data_get($this->carNames, '0');
    }

    public function render()
    {
        return view('livewire.Homeowner.homeowner-view')
            ->extends('layouts.admin')
            ->section('content');
    }
}
