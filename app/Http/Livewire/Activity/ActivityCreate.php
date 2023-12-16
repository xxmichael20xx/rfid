<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activity;
use App\Models\ActivityGallery;
use App\Models\HomeOwner;
use App\Models\Notification;
use App\Rules\EndTimeChecker;
use App\Rules\NotPastDate;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ActivityCreate extends Component
{
    use WithFileUploads;

    /**
     * The model for the home owner form
     */
    public $form = [
        'title' => '',
        'description' => '',
        'location' => '',
        'start_time' => '',
        'end_time' => '',
        'start_date' => '',
        'end_date' => '',
        'gallery' => []
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
            'form.end_time' => ['required', new EndTimeChecker($this->form)],
            'form.start_date' => ['required', 'date', new NotPastDate],
            'form.end_date' => ['required', 'date', 'after_or_equal:form.start_date'],
            'form.gallery.*' => ['nullable', 'image']
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

        if (count($this->form['gallery']) > 0) {
            foreach ($this->form['gallery'] as $key => $image) {
                $imageName = $image->store('images/activity-gallery');

                // store the image
                ActivityGallery::create([
                    'activity_id' => $newActivity->id,
                    'image' => '/uploads/' . $imageName
                ]);
            }
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
