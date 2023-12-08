<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\Visitor;
use Carbon\Carbon;
use Livewire\Component;

class GuardVisitorExit extends Component
{
    public $data;
    public $form;

    protected $listeners = [
        'showVisitorExit' => 'showVisitorExit',
    ];

    public function visitorNotes()
    {
        // get the time_in of the visitor
        $timeIn = Carbon::parse($this->data->time_in);
        $timeOut = Carbon::parse($this->data->time_out);
        $requiredRule = 'nullable';

        // check if time_out is more than >= 24-hours on time_in
        if ($timeOut->diffInHours($timeIn) >= 24) {
            $requiredRule = 'required';
        }

        // validate the notes form
        $this->validate([
            'form.notes' => [$requiredRule],
        ], [
            'form.notes.required' => 'Please enter a note since the visitor exit is more than 24-hours.'
        ]);

        // save the notes
        $this->data->update([
            'notes' => $this->form['notes']
        ]);

        $this->form = [
            'id' => '',
            'notes' => ''
        ];

        $this->emit('close.visitor-exit');
    }

    public function showVisitorExit($params)
    {
        $this->data = Visitor::find($params['id']);
        $this->form['id'] = $params['id'];

        $this->data->update([
            'time_out' => now(),
        ]);

        $this->emit('show.visitor-exit');
    }

    public function mount()
    {
        $this->form = [
            'id' => null,
            'notes' => '',
        ];
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-exit');
    }
}
