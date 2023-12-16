<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activity;
use App\Models\ActivityGallery;
use App\Rules\EndTimeChecker;
use App\Rules\NotPastDate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ActivityUpdate extends Component
{
    use WithFileUploads;
    
    public $model;
    public $modelTitle;

    /**
     * Add the validation rules for createing
     * a new home owner
     */
    protected function rules()
    {
        return [
            'model.title' => [
                'required',
                Rule::unique('activities', 'title')->ignore($this->model['id'])
            ],
            'model.description' => ['required'],
            'model.location' => ['required'],
            'model.start_time' => ['required'],
            'model.end_time' => ['required', new EndTimeChecker($this->model)],
            'model.start_date' => ['required', 'date', new NotPastDate],
            'model.end_date' => ['required', 'date', 'after_or_equal:model.start_date'],
            'model.gallery.*' => ['nullable', 'image']
        ];
    }

    /**
     * Function to validate the form and to
     * update the activity data
     */
    public function update()
    {
        // validate the form data
        $this->validate();

        // update new activity if validation is passed
        // and if activity exists
        if (! $this->model) {
             // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'info',
                'title' => 'Data Error',
                'message' => 'The activity data is not found!'
            ]);
        }

        $activity = Activity::find($this->model['id']);
        $activity->save();

        if ($gallery = data_get($this->model, 'gallery', [])) {
            // delete all gallery images
            ActivityGallery::where('activity_id', $activity->id)->each(function ($item) {
                $item->delete();
            });

            foreach ($gallery as $key => $image) {
                $imageName = $image->store('images/activity-gallery');

                // store the image
                ActivityGallery::create([
                    'activity_id' => $this->model['id'],
                    'image' => '/uploads/' . $imageName
                ]);
            }
        }
        
        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Updated Success',
            'message' => 'Activity has been successfully updated!',
            'redirect' => route('activities.list')
        ]);
    }

    public function mount($id)
    {
        $this->model = Activity::with(['galleries'])->find($id)->toArray();
        $this->modelTitle = $this->model['title'];
    }

    public function render()
    {
        return view('livewire.Activity.activity-update')
            ->extends('layouts.admin')
            ->section('content');
    }
}
