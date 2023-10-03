<?php

namespace App\Http\Livewire\Homeowner;

use App\Models\HomeOwner;
use App\Models\Profile;
use App\Rules\NotFutureDate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class HomeownerView extends Component
{
    protected $listeners = ['delete-profile' => 'deleteProfile'];

    public $data;

    public $createForm = [
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'contact_no',
        'notes',
    ];
    public $updateForm = null;
    public $toView = null;

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

    // Set the profile to update
    public function setUpdate($id)
    {
        $this->updateForm = Profile::find($id)
            ->only(['id', 'first_name', 'last_name', 'middle_name', 'date_of_birth', 'contact_no', 'notes']);

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

    public function mount($id)
    {
        $this->data = HomeOwner::with(['profiles', 'rfid', 'myBlock', 'myLot'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.homeowner.homeowner-view')
            ->extends('layouts.admin')
            ->section('content');
    }
}
