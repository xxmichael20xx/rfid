<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\HomeOwner;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GuardVisitorRequest extends Component
{
    public $requests;

    public $homeOwners;

    public $requestForm;

    public function submitRequest()
    {
        // validate the form
        $this->validate([
            'requestForm.home_owner_id' => ['required'],
            'requestForm.relation' => ['required'],
            'requestForm.details' => ['required']
        ]);

        // send the notification
        $title = 'Visitor Request';
        $content = $this->requestForm['details'];
        $content .= '. This request is from your ' . $this->requestForm['relation'];

        Notification::create([
            'home_owner_id' => $this->requestForm['home_owner_id'],
            'title' => $title,
            'content' => $content
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

    public function setVisitorFor($value)
    {
        $this->requestForm['home_owner_id'] = $value;
    }

    public function fetchLatest()
    {
        $requestsQuery = Notification::where('type', 'Visitor Request');

        if ($search = request('search')) {
            $likeSearch = '%' . $search . '%';
            $requestsQuery = $requestsQuery->where(function ($query) use ($likeSearch) {
                $query->where(function ($query) use ($likeSearch) {
                    $query->where(DB::raw("CONCAT(last_name, ', ', first_name)"), 'LIKE', $likeSearch)
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch);
                })
                ->orWhereHas('homeOwner', function ($query) use ($likeSearch) {
                    $query->where(function ($query) use ($likeSearch) {
                        $query->where(DB::raw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $likeSearch)
                                ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $likeSearch)
                                ->orWhere(DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name)"), 'LIKE', $likeSearch)
                                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch)
                                ->orWhere('first_name', 'LIKE', $likeSearch)
                                ->orWhere('middle_name', 'LIKE', $likeSearch)
                                ->orWhere('last_name', 'LIKE', $likeSearch);
                    });
                });
            });
        }
        
        $this->requests = $requestsQuery
            ->latest()
            ->get();
    }

    public function mount()
    {
        $this->fetchLatest();

        $this->homeOwners = HomeOwner::orderBy('last_name', 'ASC')->get();
        
        $this->requestForm = [
            'home_owner_id' => $this->homeOwners->first()->id,
            'relation' => 'Cousin',
            'details' => '',
        ];
    }

    public function render()
    {
        return view('livewire.Guard.Visitor.guard-visitor-request')
            ->extends('layouts.guard')
            ->section('content');
    }
}
