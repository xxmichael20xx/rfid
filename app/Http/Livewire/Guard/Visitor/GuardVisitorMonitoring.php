<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Models\Notification;
use App\Models\Visitor;
use App\Services\QRService;
use Carbon\Carbon;
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
            // check if the qr code is created 24-hours ago
            $tokenGeneratedAt = Carbon::parse($visitorToken->generated_at);
            $today = Carbon::now();
            if ($tokenGeneratedAt->diffInHours($today) >= 24) {
                // emit a new event for the notification
                $this->emit('guard.qr-processed', [
                    'icon' => 'warning',
                    'title' => 'Expired',
                    'message' => 'QR Code is invalid'
                ]);
                return false;
            }

            // check if qr code is used twice to mark: time_in & time_out
            if ($visitorToken->time_in && $visitorToken->time_out) {
                // emit a new event for the notification
                $this->emit('guard.qr-processed', [
                    'icon' => 'warning',
                    'title' => 'Expired',
                    'message' => 'QR Code is expired'
                ]);
                return false;
            }

            $isTimeIn = false;
            $isTimeOut = false;

            // check if action is time_in
            if (! $visitorToken->time_in) {
                $visitorToken->update([
                    'time_in' => now()
                ]);

                $isTimeIn = true;
            } else if (! $visitorToken->time_out) {
                $visitorToken->update([
                    'time_out' => now()
                ]);

                $isTimeOut = true;
            }

            // check if action is not time_in and time_out
            // meaning it's an invalid action/request
            if (! $isTimeIn && ! $isTimeOut) {
                // emit a new event for the notification
                $this->emit('guard.qr-processed', [
                    'icon' => 'warning',
                    'title' => 'Expired',
                    'message' => 'QR Code is expired'
                ]);
            } else {
                // check action is time_in
                if ($isTimeIn) {
                    // create new notification
                    Notification::create([
                        'home_owner_id' => $visitorToken->home_owner_id,
                        'title' => 'Visitor Entry',
                        'content' => 'You have a visitor with a name of "' . $visitorToken->last_full_name . '"'
                    ]);

                    // emit a new event for the notification
                    $this->emit('guard.qr-processed');

                    $this->emitTo('guard.visitor.guard-visitor-entry', 'showVisitorEntry', ['id' => $visitorToken->home_owner_id]);
                } else {
                    $this->emitTo('guard.visitor.guard-visitor-exit', 'showVisitorExit', ['id' => $visitorToken->id]);
                }
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
