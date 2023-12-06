<?php

namespace App\Http\Livewire\Admin\Report;

use App\Exports\ExpenseReport;
use App\Models\PaymentExpense;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportExpenses extends Component
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
        $this->records = PaymentExpense::whereBetween('transaction_date', $this->dateRange)->get();
    }

    public function exportData()
    {
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = 'report_expenses_' . $timestamp . '.xlsx';

        return Excel::download(new ExpenseReport($this->records), $filename);
    }

    public function render()
    {
        return view('livewire.admin.report.admin-report-expenses')
            ->extends('layouts.admin')
            ->section('content');
    }
}
