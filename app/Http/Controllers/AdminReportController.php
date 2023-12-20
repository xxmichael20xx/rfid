<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Payment;
use App\Models\PaymentExpense;
use App\Models\RfidMonitoring;
use App\Models\Visitor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    protected $rangeStart;

    protected $rangeEnd;

    protected $range;

    public function __construct()
    {
        $rangeStart = request()->get('range-start');
        $rangeEnd = request()->get('range-end');

        $now = Carbon::now();
        $rangeStart ??= $$now->copy()->startOfMonth()->format('Y-m-d');
        $rangeEnd ??= $$now->copy()->startOfMonth()->format('Y-m-d');

        $this->rangeStart = $rangeStart;
        $this->rangeEnd = $rangeEnd;
        $this->range = [$rangeStart, $rangeEnd];
    }

    protected function formattedRange()
    {
        $start = Carbon::parse($this->range[0])->format('M d, Y');
        $end = Carbon::parse($this->range[1])->format('M d, Y');

        return [$start, $end];
    }
    
    public function activities()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_activities_' . $timestamp . '.pdf';

        $records = Activity::where('start_date', $this->rangeStart)
            ->orWhere('end_date', $this->rangeEnd)
            ->orWhere(function($query) {
                $query->where('start_date', '>=', $this->rangeStart)
                    ->where('end_date', '<=', $this->rangeEnd);
            })->get();
        
        $pdf = Pdf::loadView('admin.report.print.print-activity', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);

        $pdf->setPaper('legal', 'landscape');

        return $pdf->download($filename);
    }

    public function expenses()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_expenses_' . $timestamp . '.pdf';

        $records = PaymentExpense::whereBetween('transaction_date', $this->range)->get();

        $pdf = Pdf::loadView('admin.report.print.print-expenses', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);

        $pdf->setPaper('legal', 'landscape');

        return $pdf->download($filename);
    }

    public function payments()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_payments_' . $timestamp . '.pdf';

        $filterBy = request()->get('filter-by', 'all');
        $whereIn = $filterBy == 'all' ? ['pending', 'paid'] : [$filterBy];
        $records = Payment::whereBetween('due_date', $this->range)->whereIn('status', $whereIn)->get();

        $pdf = Pdf::loadView('admin.report.print.print-payments', [
            'records' => $records,
            'filterBy' => $filterBy,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);

        $pdf->setPaper('legal', 'landscape');

        return $pdf->download($filename);
    }

    public function visitors()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_visitors_' . $timestamp . '.pdf';

        $records = Visitor::whereBetween('time_in', $this->range)->get();
        
        $pdf = Pdf::loadView('admin.report.print.print-visitors', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);

        $pdf->setPaper('legal', 'landscape');

        return $pdf->download($filename);
    }

    public function rfids()
    {
        $prefix = match (auth()->user()->role) {
            'Admin' => 'president_',
            'Guard' => 'guard_',
            default => 'treasurer_'
        };
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = $prefix . 'report_rfids_' . $timestamp . '.pdf';

        $records = RfidMonitoring::with(['rfidData.vehicle.homeOwner'])->whereBetween('created_at', $this->range)->get();
        
        $pdf = Pdf::loadView('admin.report.print.print-rfids', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);

        $pdf->setPaper('legal', 'landscape');

        return $pdf->download($filename);
    }
}
