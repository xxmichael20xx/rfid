<?php

namespace App\Http\Livewire\Admin\Report;

use App\Exports\RfidReport;
use App\Models\RfidMonitoring;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportRfid extends Component
{
    public $records;

    public $dateRange;

    public function mount()
    {
        $now = Carbon::now();
        $this->dateRange = [
            $now->copy()->startOfMonth()->format('Y-m-d'),
            $now->copy()->endOfMonth()->format('Y-m-d')
        ];

        $this->loadRecords();
    }

    public function loadRecords()
    {
        $this->records = RfidMonitoring::with(['rfidData.vehicle.homeOwner'])->whereBetween('time_in', $this->dateRange)->get();
    }

    public function exportData()
    {
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = 'report_rfid_monitoring_' . $timestamp . '.xlsx';

        return Excel::download(new RfidReport($this->records), $filename);
    }

    public function render()
    {
        return view('livewire.admin.report.admin-report-rfid')
            ->extends('layouts.' . str(auth()->user()->role)->lower())
            ->section('content');
    }
}
