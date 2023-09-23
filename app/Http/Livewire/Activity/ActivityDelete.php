<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activity;
use Livewire\Component;

class ActivityDelete extends Component
{
    public $modelId;
    public $modelKey;

    /**
     * Function to delete the Activity
     */
    public function delete()
    {
        // delete the activity
        Activity::find($this->modelId)->delete();

        // send an event for notification
        $this->emit('show.dialog', [
            'icon' => 'info',
            'title' => 'Delete Success',
            'message' => 'Activity has been successfully deleted!!',
            'redirect' => route('activities.list')
        ]);
    }

    /**
     * Mount function to initialize dynamic data
     */
    public function mount()
    {
        $this->modelKey = 'activity-' . $this->modelId;
    }

    /**
     * Function to render the blade file
     */
    public function render()
    {
        return view('livewire.activity.activity-delete');
    }
}
