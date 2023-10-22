<?php

namespace App\Http\Livewire\Payments;

use Livewire\Component;

class PaymentsOverview extends Component
{
    /**
     * Initialize component data
     */
    public function mount()
    {
        // 
    }

    /**
     * Define what blade file to render
     */
    public function render()
    {
        return view('livewire.payments.payments-overview')
            ->extends('layouts.admin')
            ->section('content');
    }
}
