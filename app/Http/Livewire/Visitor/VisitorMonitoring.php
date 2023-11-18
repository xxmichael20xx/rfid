<?php

namespace App\Http\Livewire\Visitor;

use App\Models\Visitor;
use Livewire\Component;

class VisitorMonitoring extends Component
{
    public $visitors;

    public function mount()
    {
        $this->visitors = Visitor::with('for')
            ->where('date_visited', '!=', null)
            ->orderBy('date_visited', 'DESC')
            ->get();
    }

    public function render()
    {
        return view('livewire.Visitor.visitor-monitoring')
            ->extends('layouts.admin')
            ->section('content');
    }
}
