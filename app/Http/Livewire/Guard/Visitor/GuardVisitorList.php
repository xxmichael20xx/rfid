<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\HomeOwner;
use App\Models\Notification;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GuardVisitorList extends Component
{
    public $visitors;

    public $homeOwners;

    public $requestForm;

    public function submitRequest()
    {
        // validate the form
        $this->validate([
            'requestForm.home_owner_id' => ['required'],
            'requestForm.details' => ['required']
        ]);

        // send the notification
        $title = 'Visitor Request';
        Notification::create([
            'home_owner_id' => $this->requestForm['home_owner_id'],
            'title' => $title,
            'content' => $this->requestForm['details']
        ]);

        $this->requestForm = [
            'home_owner_id' => $this->homeOwners->first()->id,
            'details' => '',
        ];

        // emit a new event for the notification
        $this->emit('guard.request-success', [
            'icon' => 'success',
            'title' => 'Request Sent!',
            'message' => 'Wait for the home owner to generate your QR Code.'
        ]);
    }

    public function mount()
    {
        $visitorQuery = Visitor::with('for')
            ->whereNotNull('date_visited');

        if ($search = request('search')) {
            $likeSearch = '%' . $search . '%';
            $visitorQuery = $visitorQuery->where(function ($query) use ($likeSearch) {
                $query->where(DB::raw("CONCAT(last_name, ', ', first_name)"), 'LIKE', $likeSearch)
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch);
            })->orWhereHas('for', function ($query) use ($likeSearch) {
                $query->where(function ($query) use ($likeSearch) {
                    $query->where(DB::raw("CONCAT(last_name, ', ', first_name, COALESCE(', ', middle_name, ''))"), 'LIKE', $likeSearch)
                        ->orWhere(DB::raw("CONCAT(first_name, COALESCE(' ', middle_name, ''), ' ', last_name)"), 'LIKE', $likeSearch);
                });
            });
        }

        $this->visitors = $visitorQuery
            ->orderByDesc('date_visited')
            ->get();

        $this->homeOwners = HomeOwner::orderBy('last_name', 'ASC')->get();

        $this->requestForm = [
            'home_owner_id' => $this->homeOwners->first()->id,
            'details' => '',
        ];
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-list')
            ->extends('layouts.guard')
            ->section('content');
    }
}
