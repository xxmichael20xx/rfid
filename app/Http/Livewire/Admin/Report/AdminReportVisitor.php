<?php

namespace App\Http\Livewire\Admin\Report;

use App\Exports\VisitorReport;
use App\Models\Visitor;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportVisitor extends Component
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
        $this->records = Visitor::whereBetween('time_in', $this->dateRange)->get();
    }

    public function exportData()
    {
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = 'report_visitors_' . $timestamp . '.xlsx';

        return Excel::download(new VisitorReport($this->records), $filename);
    }

    public function render()
    {
        return view('livewire.admin.report.admin-report-visitor')
            ->extends('layouts.' . str(auth()->user()->role)->lower())
            ->section('content');
    }
}
