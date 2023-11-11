<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\HomeOwnerBlockLot;
use App\Models\HomeOwnerVehicle;
use App\Models\Lot;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Rfid;
use App\Models\User;
use App\Notifications\UserAccountCreated;
use App\Rules\BlockLots;
use App\Rules\LegalBirthDate;
use App\Rules\NotFutureDate;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeownerCreate extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'selectLot',
        'unSelectLot',
        'selectPayment',
        'unSelectPayment',
    ];
    public $availableLBlockLots = [];

    /**
     * The model for the homeowner form
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
                'car_type' => '',
                'rfid' => ''
            ]
        ],
        'payments' => []
    ];

    /**
     * List of payment types
     */
    public $paymentTypes;

    /**
     * Add the validation rules for createing
     * a new homeowner
     */
    protected function rules()
    {
        $nameRules = ['required', 'string', 'min:2', 'max:30'];

        return [
            'form.first_name' => $nameRules,
            'form.last_name' => $nameRules,
            'form.middle_name' => ['string', 'min:2', 'max:30'],
            'form.date_of_birth' => ['required', 'date', new NotFutureDate, new LegalBirthDate],
            'form.block_lots' => [new BlockLots],
            'form.contact_no' => ['required', 'regex:/^09\d{9}$/', Rule::unique('home_owners', 'contact_no')],
            'form.email' => ['required', 'email', Rule::unique('home_owners', 'email')],
            'form.profile' => ['nullable', 'image'],
            'form.vehicles.*.plate_number' => [
                'sometimes',
                'nullable',
                'distinct',
                'required_with:form.vehicles.*.car_type',
                Rule::unique('home_owner_vehicles', 'plate_number')
            ],
            'form.vehicles.*.car_type' => ['sometimes', 'nullable', 'required_with:form.vehicles.*.plate_number'],
            'form.vehicles.*.rfid' => ['sometimes', 'nullable', 'distinct', Rule::unique('rfids', 'rfid')],
            'form.payments' => ['nullable']
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
            'form.vehicles.*.rfid.distinct' => 'The rfid should be unique.',
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
             // dispatch a javascript event to trigger the notification
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

        // add payments
        $this->processPayments($newHomeOwner->id);

        // create a user account
        $this->createAccount($newHomeOwner->toArray());

        // dispatch a javascript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'New Home Owner has been successfully created!',
            'redirect' => route('homeowners.view', ['id' => $newHomeOwner->id])
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
                $homeOwnerVehicle = HomeOwnerVehicle::create([
                    'home_owner_id' => $homeOwnerId,
                    'plate_number' => $vehicle['plate_number'],
                    'car_type' => $vehicle['car_type']
                ]);

                if ($rfid = data_get($vehicle, 'rfid')) {
                    Rfid::create([
                        'vehicle_id' => $homeOwnerVehicle->id,
                        'rfid' => $rfid
                    ]);
                }
            }
        }
    }

    /**
     * Create a new account
     */
    public function createAccount($homeOwner)
    {
        $accountData = Arr::only($homeOwner, [
            'first_name',
            'last_name',
            'middle_name',
            'email'
        ]);

        $password = 'Password1';
        data_set($accountData, 'password', bcrypt($password));
        data_set($accountData, 'role', 'User');
        data_set($accountData, 'home_owner_id', $homeOwner->id);

        $user = User::create($accountData);

        $user->notify(new UserAccountCreated($password));
    }

    /**
     * Process the selected payments
     */
    public function processPayments($homeOwnerId)
    {
        $payments = (array) $this->form['payments'];

        // Get the current year and month
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        if ($payments) {
            foreach ($payments as $payment) {
                $paymentId = (int) ($payment);
                $paymentType = PaymentType::find($paymentId);
                $selectedDate = Carbon::create($currentYear, $currentMonth, $paymentType->recurring_day);
                $dueDate = Carbon::now();

                // Check if the selected date is within the current month and not a past date
                if ($selectedDate->isCurrentMonth() && $selectedDate->isFuture()) {
                    $dueDate = $selectedDate;
                } else {
                    $frequency = $paymentType->frequency;
                    $recurringDate = (int) $paymentType->recurring_day;

                    if ($frequency === 'monthly') {
                        // For monthly payments
                        $dueDate->addMonthsNoOverflow()->day($recurringDate);
                    } elseif ($frequency === 'annually') {
                        // For annually payments
                        $dueDate->addYear()->day($recurringDate);
                    }
                }

                Payment::create([
                    'home_owner_id' => $homeOwnerId,
                    'type_id' => $paymentId,
                    'amount' => $paymentType->amount,
                    'due_date' => $dueDate,
                    'is_recurring' => $paymentType->is_recurring
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

    public function selectPayment($id)
    {
        $this->form['payments'][] = $id;
    }

    public function unSelectPayment($id)
    {
        // Remove the value 'block' from the array
        $key = array_search($id, (array) $this->form['payments']);
        if ($key !== false) {
            unset($this->form['payments'][$key]);
        }

        // Re-index the array
        $this->form['payments'] = array_values((array) $this->form['payments']);
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

        $this->paymentTypes = PaymentType::orderBy('type', 'asc')->get();
    }

    /**
     * Render the .blade.php file
     */
    public function render()
    {
        return view('livewire.Homeowner.homeowner-create')
            ->extends('layouts.admin')
            ->section('content');
    }
}
