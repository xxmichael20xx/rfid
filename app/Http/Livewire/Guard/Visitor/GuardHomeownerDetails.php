<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\HomeOwner;
use Livewire\Component;

class GuardHomeownerDetails extends Component
{
    protected $listeners = [
        'showHomeownerDetails' => 'showHomeownerDetails'
    ];

    public $data;

    public function showHomeownerDetails($params)
    {
        $this->data = HomeOwner::find($params['id']);

        $this->emit('show.homeowner-details');
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-homeowner-details');
    }
}
