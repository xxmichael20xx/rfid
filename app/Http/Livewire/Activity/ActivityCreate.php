<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activity;
use App\Models\HomeOwner;
use App\Models\Notification;
use App\Rules\NotPastDate;
use Carbon\Carbon;
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
        'start_time' => '',
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
            'form.start_time' => ['required'],
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
        $newActivity = Activity::create($this->form);
        if (! $newActivity) {
             // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'info',
                'title' => 'Create Failed',
                'message' => 'Failed to create new Activity!'
            ]);
        }

        // create new notification to all home owners
        $this->notifyAll($newActivity);
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'New activity has been successfully created!',
            'redirect' => route('activities.list')
        ]);
    }

    public function notifyAll($newActivity)
    {
        $homeOwners = HomeOwner::all();
        $title = 'New Activity';
        $content = 'Activity "'. $newActivity->title .'" will start on '. $newActivity->start_date .' @ '. Carbon::parse($newActivity->start_time)->format('h:ia') .' and will end on '. $newActivity->end_date .'!';
        $content .= ' The location is at "'. $newActivity->location .'", See you there!';

        $homeOwners->each(function($item) use ($title, $content) {
            // create notification
            Notification::create([
                'home_owner_id' => $item->id,
                'title' => $title,
                'content' => $content
            ]);
        });
    }
    
    /**
     * Render the .blade.php file
     */
    public function render()
    {
        return view('livewire.Activity.activity-create')
            ->extends('layouts.admin')
            ->section('content');
    }
}
