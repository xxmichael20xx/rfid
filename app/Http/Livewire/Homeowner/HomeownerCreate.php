<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\HomeOwnerBlockLot;
use App\Models\HomeOwnerVehicle;
use App\Models\Lot;
use App\Rules\NotFutureDate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeownerCreate extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'selectLot',
        'unSelectLot'
    ];
    public $availableLBlockLots = [];

    /**
     * The model for the home owner form
     */
    public $form = [
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'gender' => 'male',
        'date_of_birth' => '',
        'block_lots' => [],
        'contact_no' => '',
        'email' => '',
        'profile' => null,
        'vehicles' => [
            [
                'plate_number' => '',
                'car_type' => ''
            ]
        ]
    ];

    /**
     * Add the validation rules for createing
     * a new home owner
     */
    protected function rules()
    {
        $nameRules = ['required', 'string', 'min:2', 'max:30'];

        return [
            'form.first_name' => $nameRules,
            'form.last_name' => $nameRules,
            'form.middle_name' => ['string', 'min:2', 'max:30'],
            'form.date_of_birth' => ['required', 'date', new NotFutureDate],
            'form.block_lots' => ['required', 'array', 'min:1'],
            'form.contact_no' => ['required', 'regex:/^09\d{9}$/', Rule::unique('home_owners', 'contact_no')],
            'form.email' => ['nullable', 'email'],
            'form.profile' => ['nullable', 'image'],
            'form.vehicles.*.plate_number' => [
                'sometimes',
                'nullable',
                'distinct',
                'required_with:form.vehicles.*.car_type',
                Rule::unique('home_owner_vehicles', 'plate_number')
            ],
            'form.vehicles.*.car_type' => ['sometimes', 'nullable', 'required_with:form.vehicles.*.plate_number']
        ];
    }

    /**
     * Validate and create a new homeowner
     */
    public function create()
    {
        // validate the form data
        $this->validate($this->rules(), [
            'form.contact_no.regex' => 'Contact number format is invalid, valid format is: 09123456789',
            'form.vehicles.*.plate_number.distinct' => 'The plate number should be unique.',
            'form.vehicles.*.plate_number.required_with' => 'The plate number is required when the car type is provided.',
            'form.vehicles.*.plate_number.unique' => 'The plate number is already taken.',
            'form.vehicles.*.car_type.required_with' => 'The car type is required when the plate number is provided.',
        ]);

        // Handle image upload
        if ($profile = $this->form['profile']) {
            $this->form['profile'] = Storage::putFileAs('images/home-owners', $profile, $profile->hashName());
        } else {
            $profile = 'images/default_male.jpg';

            if ($this->form['gender'] == 'female') {
                $profile = 'images/default_female.jpg';
            }

            $this->form['profile'] = $profile;
        }

        // create a new home owner if validation is passed
        // and if new home owner is created
        $newHomeOwner = HomeOwner::create($this->form);
        if (! $newHomeOwner) {
             // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'info',
                'title' => 'Create Failed',
                'message' => 'Failed to create new Home Owner!'
            ]);
        }

        // remove the selected profile from the page
        $this->form['profile'] = null;

        // add the selected block & lots
        $this->processBlockAndLots($newHomeOwner->id);

        // add the vehicles
        $this->processVehicles($newHomeOwner->id);
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'New Home Owner has been successfully created!',
            'redirect' => route('homeowners.list')
        ]);
    }

    /**
     * Add the selected block and lots
     */
    public function processBlockAndLots($homeOwnerId)
    {
        $blockLots = (array) $this->form['block_lots'];

        foreach ($blockLots as $blockLot) {
            $lot = Lot::with('block')->find($blockLot);
            $block = $lot->block->id;

            HomeOwnerBlockLot::create([
                'home_owner_id' => $homeOwnerId,
                'block' => $block,
                'lot' => $blockLot
            ]);

            Lot::find($blockLot)->update([
                'availability' => 'unavailable'
            ]);
        }
    }

    /**
     * Add the vehicles (if any)
     */
    public function processVehicles($homeOwnerId)
    {
        $vehicles = (array) $this->form['vehicles'];

        if (count($vehicles) > 0) {
            foreach ($vehicles as $vehicle) {
                HomeOwnerVehicle::create([
                    'home_owner_id' => $homeOwnerId,
                    'plate_number' => $vehicle['plate_number'],
                    'car_type' => $vehicle['car_type']
                ]);
            }
        }
    }

    public function selectLot($id)
    {
        $this->form['block_lots'][] = $id;
    }

    public function unSelectLot($id)
    {
        // Remove the value 'block' from the array
        $key = array_search($id, (array) $this->form['block_lots']);
        if ($key !== false) {
            unset($this->form['block_lots'][$key]);
        }

        // Re-index the array
        $this->form['block_lots'] = array_values((array) $this->form['block_lots']);
    }

    public function addVehicle() {
        $this->form['vehicles'][] = [
            'plate_number' => '',
            'car_type' => ''
        ];
    }

    public function removeVehicle($key)
    {
        unset($this->form['vehicles'][$key]);
        $this->form['vehicles'] = array_values((array) $this->form['vehicles']); // Re-index the array
    }

    public function mount()
    {
        foreach (Block::all() as $block) {
            $lots = Lot::where('block_id', $block->id)
                ->where('availability', 'available')
                ->pluck('id', 'lot')->toArray();

            if (count($lots) > 0) {
                $this->availableLBlockLots[$block->block] = $lots;
            }
        }
    }
    
    /**
     * Render the .blade.php file
     */
    public function render()
    {
        return view('livewire.homeowner.homeowner-create')
            ->extends('layouts.admin')
            ->section('content');
    }
}
