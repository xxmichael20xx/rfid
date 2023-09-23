<?php

namespace App\Http\Livewire\Profile;

use App\Models\Profile;
use Livewire\Component;

class ProfileView extends Component
{
    public $profile;
    public $profileId;

    public function mount()
    {
        $this->profile = Profile::find($this->profileId);
    }

    public function render()
    {
        return view('livewire.profile.profile-view');
    }
}
