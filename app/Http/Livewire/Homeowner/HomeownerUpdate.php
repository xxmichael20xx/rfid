<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\HomeOwnerBlockLot;
use App\Models\Lot;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeownerUpdate extends Component
{
    use WithFileUploads;

    public $model;
    public $modelFullName;
    public $lotsCarousels;

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
            'model.middle_name' => ['nullable', 'min:2', 'max:30'],
            'model.gender' => ['required'],
            'model.contact_no' => [
                'required',
                'regex:/^(\\+639|09)\\d{9}$|^\\(?(\\d{3})\\)?[- ]?(\\d{3})[- ]?(\\d{4})$/',
                Rule::unique('home_owners', 'contact_no')->ignore($this->model['id'])
            ],
            'model.email' => ['nullable', 'email'],
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
        } else {
            if (str_contains($this->model['profile'], 'default_')) {
                $profile = 'images/default_male.jpg';

                if ($this->model['gender'] == 'female') {
                    $profile = 'images/default_female.jpg';
                }

                $this->model['profile'] = $profile;
            } else {
                $this->model = Arr::except($this->model, 'profile');
            }
        }

        $middleName = $this->model['middle_name'];
        if (empty($middleName) || $middleName == '') {
            $this->model['middle_name'] = NULL;
        }
        HomeOwner::find($this->model['id'])->update($this->model);
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Updated Success',
            'message' => 'Home Owner has been successfully updated!',
            'redirect' => route('homeowners.list')
        ]);
    }

    public function mount($id)
    {
        $homeOwner = HomeOwner::find($id);
        $this->model = $homeOwner->toArray();
        $this->modelFullName = $this->model['full_name'];

        // set the lot carousel
        $blockLots = $homeOwner->blockLots;
        $this->lotsCarousels = collect($blockLots)->map(function($item) {
            $block = Block::find($item->block);
            $lot = Lot::find($item->lot);

            if ($lotImage = $lot->image) {
                return [
                    'name' => sprintf('Block %s - Lot %s', $block->block, $lot->lot),
                    'image' => '/uploads/' . $lotImage
                ];
            }
        })->filter()->all();
    }

    public function render()
    {
        return view('livewire.Homeowner.homeowner-update')
            ->extends('layouts.admin')
            ->section('content');
    }
}
