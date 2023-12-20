<?php

namespace App\Http\Livewire\Admin\Report;

use App\Exports\ActivityReport;
use App\Models\Activity;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportActivity extends Component
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
        $this->records = Activity::where('start_date', $this->dateRange[0])
            ->orWhere('end_date', $this->dateRange[1])
            ->orWhere(function($query) {
                $query->where('start_date', '>=', $this->dateRange[0])
                    ->where('end_date', '<=', $this->dateRange[1]);
            })->get();
    }

    public function exportData()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_activities_' . $timestamp . '.xlsx';

        return Excel::download(new ActivityReport($this->records), $filename);
    }

    public function render()
    {
        return view('livewire.admin.report.admin-report-activity')
            ->extends('layouts.' . str(auth()->user()->role)->lower())
            ->section('content');
    }
}
