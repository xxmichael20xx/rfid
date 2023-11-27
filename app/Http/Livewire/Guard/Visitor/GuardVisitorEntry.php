<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\HomeOwner;
use Livewire\Component;

class GuardVisitorEntry extends Component
{
    protected $listeners = [
        'showVisitorEntry' => 'showVisitorEntry'
    ];

    public $data;

    public function showVisitorEntry($params)
    {
        $this->data = HomeOwner::find($params['id']);

        $this->emit('show.visitor-entry');
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-entry');
    }
}
