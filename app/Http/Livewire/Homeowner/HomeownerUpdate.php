<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\Lot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeownerUpdate extends Component
{
    use WithFileUploads;

    public $model;
    public $modelFullName;
    public $modelCurrentLot;
    public $blocks = [];
    public $lots = [];

    /**
     * Add the validation rules for createing
     * a new home owner
     */
    protected function rules()
    {
        $nameRules = ['required', 'string', 'min:2', 'max:30'];

        return [
            'model.first_name' => $nameRules,
            'model.last_name' => $nameRules,
            'model.middle_name' => ['string', 'min:2', 'max:30'],
            'model.block' => ['required'],
            'model.lot' => ['required'],
            'model.contact_no' => [
                'required',
                'regex:/^09\d{9}$/',
                Rule::unique('home_owners', 'contact_no')->ignore($this->model['id'])
            ],
            'model.profileUpdate' => ['nullable', 'image']
        ];
    }

    /**
     * Function to validate the form and to
     * update the home owner data
     */
    public function update()
    {
        // validate the form data
        $this->validate(
            $this->rules(),
            [
                'model.contact_no.regex' => 'Contact number format is invalid, valid format is: 09123456789'
            ]
        );

        // update new home owner if validation is passed
        // and if home owner exists
        if (! $this->model) {
             // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'info',
                'title' => 'Data Error',
                'message' => 'The Home Owner data is not found!'
            ]);
        }

        // Handle image upload
        if ($profileUpdate = data_get($this->model, 'profileUpdate', null)) {
            $this->model['profile'] = Storage::putFileAs('images/home-owners', $profileUpdate, $profileUpdate->hashName());
        }

        // set the selected lot
        HomeOwner::find($this->model['id'])->update($this->model);

        // check if the lot has ben changed
        if ($this->modelCurrentLot !== $this->model['lot']) {
            // set the selected lot as 'available'
            Lot::find($this->modelCurrentLot)->update([
                'availability' => 'available'
            ]);

            // set the selected lot as 'unavailable'
            Lot::find($this->model['lot'])->update([
                'availability' => 'unavailable'
            ]);
        }
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Updated Success',
            'message' => 'Home Owner has been successfully updated!',
            'redirect' => route('homeowners.list')
        ]);
    }

    public function setLots()
    {
        // get and assign the available lots of the selected block
        $this->lots = Lot::where('availability', 'available')
            ->where('block_id', $this->model['block'])
            ->orWhere('id', $this->modelCurrentLot)
            ->get();
        
        // reset the selected lot value
        $this->model['lot'] = '';
    }

    public function mount($id)
    {
        $this->model = HomeOwner::find($id)->toArray();
        $this->modelFullName = $this->model['full_name'];
        $this->modelCurrentLot = $this->model['lot'];

        $this->blocks = Block::all();
        $this->lots = Lot::where('availability', 'available')
            ->where('block_id', $this->model['block'])
            ->orWhere('id', $this->model['lot'])
            ->get();
    }

    public function render()
    {
        return view('livewire.homeowner.homeowner-update')
            ->extends('layouts.admin')
            ->section('content');
    }
}
