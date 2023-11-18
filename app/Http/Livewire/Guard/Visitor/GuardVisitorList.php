<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\Visitor;
use Livewire\Component;

class GuardVisitorList extends Component
{
    public $visitors;

    public function mount()
    {
        $this->visitors = Visitor::with('for')
            ->where('date_visited', '<>', null)
            ->orderBy('date_visited', 'DESC')
            ->get();
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-list')
            ->extends('layouts.guard')
            ->section('content');
    }
}
