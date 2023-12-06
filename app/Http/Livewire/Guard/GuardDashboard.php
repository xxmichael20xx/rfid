<?php

namespace App\Http\Livewire\Guard;

use App\Models\Visitor;
use Livewire\Component;

class GuardDashboard extends Component
{
    public $visitorsToday;

    public function mount()
    {
        // set the visitors today
        $this->visitorsToday = Visitor::whereDate('time_in', now())->count();
    }

    public function render()
    {
        return view('livewire.Guard.guard-dashboard')
            ->extends('layouts.guard')
            ->section('content');
    }
}
