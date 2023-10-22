<?php

namespace App\Http\Livewire\Guard\Visitor;

use App\Services\QRService;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuardVisitorMonitoring extends Component
{
    protected $qrService;

    public function __construct()
    {
        $this->qrService = new QRService;
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
