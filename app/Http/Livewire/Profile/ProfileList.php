<?php

namespace App\Http\Livewire\Profile;

use App\Models\Profile;
use Livewire\Component;

class ProfileList extends Component
{
    public $profiles = null;

    public function mount()
    {
        $this->profiles = Profile::with('homeOwner')->get();
    }

    public function render()
    {
        return view('livewire.Profile.profile-list')
            ->extends('layouts.admin')
            ->section('content');
    }
}
