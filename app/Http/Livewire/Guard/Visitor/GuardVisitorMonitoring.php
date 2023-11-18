<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\Notification;
use App\Models\Visitor;
use App\Services\QRService;
use Livewire\Component;

class GuardVisitorMonitoring extends Component
{
    /**
     * The component listeners
     */
    protected $listeners = [
        'validateQrCode'
    ];
    protected $qrService;

    public $qrCodeUrl;

    public function __construct()
    {
        $this->qrService = new QRService;

        parent::__construct();
    }

    /**
     * Validate and process the QR Code
     */
    public function validateQrCode($token)
    {
        $visitorToken = Visitor::where('token', $token)->first();

        if (! $visitorToken) {
            // emit a new event for the notification
            $this->emit('guard.qr-processed', [
                'icon' => 'warning',
                'title' => 'Invalid',
                'message' => 'QR Code is invalid'
            ]);
        } else {
            if ($visitorToken->date_visited) {
                // emit a new event for the notification
                $this->emit('guard.qr-processed', [
                    'icon' => 'warning',
                    'title' => 'Expired',
                    'message' => 'QR Code is expired'
                ]);
            } else {
                $visitorToken->update([
                    'date_visited' => now()
                ]);

                // create new notification
                Notification::create([
                    'home_owner_id' => $visitorToken->home_owner_id,
                    'title' => 'Visitor Entry',
                    'content' => 'You have a visitor with a name of "' . $visitorToken->last_full_name . '"'
                ]);

                // emit a new event for the notification
                $this->emit('guard.qr-processed', [
                    'icon' => 'success',
                    'title' => 'Welcome Visitor!',
                    'message' => 'Have a good day!'
                ]);

                $this->emitTo('guard.visitor.guard-homeowner-details', 'showHomeownerDetails', ['id' => $visitorToken->home_owner_id]);
            }
        }
    }

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
        return view('livewire.Guard.Visitor.guard-visitor-monitoring')
            ->extends('layouts.guard')
            ->section('content');
    }
}
