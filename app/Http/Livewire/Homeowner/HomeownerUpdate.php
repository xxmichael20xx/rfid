<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\Lot;
use Illuminate\Validation\Rule;
use Livewire\Component;

class HomeownerUpdate extends Component
{
    public $model;
    public $modelFullName;
    public $modelCurrentLot;
    public $modelSelectedLot;
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
            'modelSelectedLot' => ['required'],
            'model.contact_no' => [
                'required',
                'regex:/^(09|\+639)\d{9}$/',
                Rule::unique('home_owners', 'contact_no')->ignore($this->model->id)
            ]
        ];
    }

    /**
     * Function to validate the form and to
     * update the home owner data
     */
    public function update()
    {
        // validate the form data
        $this->validate();

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

        // set the selected lot
        $this->model->lot = $this->modelSelectedLot;
        $this->model->save();

        // check if the lot has ben changed
        if ($this->modelCurrentLot !== $this->modelSelectedLot) {
            // set the selected lot as 'available'
            Lot::find($this->modelCurrentLot)->update([
                'availability' => 'available'
            ]);

            // set the selected lot as 'unavailable'
            Lot::find($this->modelSelectedLot)->update([
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
            ->where('block_id', $this->model->block)
            ->orWhere('id', $this->modelCurrentLot)
            ->get();
        
        // reset the selected lot value
        $this->modelSelectedLot = '';
    }

    public function mount($id)
    {
        $this->model = HomeOwner::find($id);
        $this->modelFullName = $this->model->full_name;
        $this->modelSelectedLot = $this->modelCurrentLot = $this->model->lot;

        $this->blocks = Block::all();
        $this->lots = Lot::where('availability', 'available')
            ->where('block_id', $this->model->block)
            ->orWhere('id', $this->model->lot)
            ->get();
    }

    public function render()
    {
        return view('livewire.homeowner.homeowner-update')
            ->extends('layouts.admin')
            ->section('content');
    }
}
