<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activity;
use App\Rules\NotPastDate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ActivityCreate extends Component
{
    /**
     * The model for the home owner form
     */
    public $form = [
        'title' => '',
        'description' => '',
        'location' => '',
        'start_date' => '',
        'end_date' => '',
    ];

    /**
     * Add the validation rules for createing
     * a new home owner
     */
    protected function rules()
    {
        return [
            'form.title' => ['required', Rule::unique('activities', 'title')],
            'form.description' => ['required'],
            'form.location' => ['required'],
            'form.start_date' => ['required', 'date', new NotPastDate],
            'form.end_date' => ['required', 'date', 'after_or_equal:form.start_date'],
        ];
    }

    /**
     * Validate and create a new activity
     */
    public function create()
    {
        // validate the form data
        $this->validate();

        // create a new activity if validation is passed
        // and if new activity is created
        if (! Activity::create($this->form)) {
             // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'info',
                'title' => 'Create Failed',
                'message' => 'Failed to create new Activity!'
            ]);
        }
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'New activity has been successfully created!',
            'redirect' => route('activities.list')
        ]);
    }
    
    /**
     * Render the .blade.php file
     */
    public function render()
    {
        return view('livewire.activity.activity-create')
            ->extends('layouts.admin')
            ->section('content');
    }
}
