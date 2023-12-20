<?php

namespace App\Http\Livewire\Admin\Report;

use App\Exports\PaymentReport;
use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportPayment extends Component
{
    public $records;

    public $dateRange;

    public $filterBy;

    public function mount()
    {
        $now = Carbon::now();
        $this->dateRange = [
            $now->copy()->startOfMonth()->format('Y-m-d'),
            $now->copy()->endOfMonth()->format('Y-m-d')
        ];
        $this->filterBy = 'all';

        $this->loadRecords();
    }

    public function loadRecords()
    {
        $whereIn = $this->filterBy == 'all' ? ['pending', 'paid'] : [$this->filterBy];
        $this->records = Payment::whereBetween('due_date', $this->dateRange)->whereIn('status', $whereIn)->get();
    }

    public function exportData()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_payments_' . $this->filterBy . '_' . $timestamp . '.xlsx';

        return Excel::download(new PaymentReport($this->records), $filename);
    }

    public function render()
    {
        return view('livewire.admin.report.admin-report-payment')
            ->extends('layouts.' . str(auth()->user()->role)->lower())
            ->section('content');
    }
}
