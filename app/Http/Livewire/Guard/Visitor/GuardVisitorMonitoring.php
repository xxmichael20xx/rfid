<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Services\QRService;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuardVisitorMonitoring extends Component
{
    protected $qrService;

    public $qrCodeUrl;

    public function __construct()
    {
        $this->qrService = new QRService;
    }

    public function downloadQRCode()
    {
        $route = route('download.qr', [], false); // The third argument sets the absolute parameter to false

        return redirect($route);
    }

    /**
     * Initialize component data
     */
    public function mount()
    {
        // $this->qrService->generateQr(1);
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
