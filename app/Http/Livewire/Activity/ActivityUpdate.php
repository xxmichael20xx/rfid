<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activity;
use App\Rules\NotPastDate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ActivityUpdate extends Component
{
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
                Rule::unique('activities', 'title')->ignore($this->model->id)
            ],
            'model.description' => ['required'],
            'model.location' => ['required'],
            'model.end_date' => ['required', new NotPastDate],
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

        $this->model->save();
        
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
        $this->model = Activity::find($id);
        $this->modelTitle = $this->model->title;
    }

    public function render()
    {
        return view('livewire.activity.activity-update')
            ->extends('layouts.admin')
            ->section('content');
    }
}
