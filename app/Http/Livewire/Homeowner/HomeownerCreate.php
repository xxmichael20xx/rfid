<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\Lot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeownerCreate extends Component
{
    use WithFileUploads;

    public $blocks = [];
    public $lots = [];

    /**
     * The model for the home owner form
     */
    public $form = [
        'first_name' => '',
        'last_name' => '',
        'middle_name' => '',
        'block' => '',
        'lot' => '',
        'contact_no' => '',
        'profile' => null
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
            'form.block' => ['required'],
            'form.lot' => ['required'],
            'form.contact_no' => ['required', 'regex:/^09\d{9}$/', Rule::unique('home_owners', 'contact_no')],
            'form.profile' => ['required', 'image']
        ];
    }

    /**
     * Validate and create a new homeowner
     */
    public function create()
    {
        // validate the form data
        $this->validate($this->rules(), ['form.contact_no.regex' => 'Contact number format is invalid, valid format is: 09123456789']);

        // Handle image upload
        if ($profile = $this->form['profile']) {
            $this->form['profile'] = Storage::putFileAs('images/home-owners', $profile, $profile->hashName());
        }

        // create a new home owner if validation is passed
        // and if new home owner is created
        if (! HomeOwner::create($this->form)) {
             // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'info',
                'title' => 'Create Failed',
                'message' => 'Failed to create new Home Owner!'
            ]);
        }

        $this->form['profile'] = null;

        // set the selected lot as 'unavailable'
        Lot::find($this->form['lot'])->update([
            'availability' => 'unavailable'
        ]);
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'New Home Owner has been successfully created!',
            'redirect' => route('homeowners.list')
        ]);
    }

    public function setLots()
    {
        // get and assign the available lots of the selected block
        $this->lots = Lot::where('block_id', $this->form['block'])
            ->where('availability', 'available')
            ->get();
        
        // reset the selected lot value
        $this->form['lot'] = '';
    }

    public function mount()
    {
        $this->blocks = Block::all();
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
