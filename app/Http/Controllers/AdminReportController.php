<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Payment;
use App\Models\PaymentExpense;
use App\Models\RfidMonitoring;
use App\Models\Visitor;
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
        $records = Activity::where('start_date', $this->rangeStart)
            ->orWhere('end_date', $this->rangeEnd)
            ->orWhere(function($query) {
                $query->where('start_date', '>=', $this->rangeStart)
                    ->where('end_date', '<=', $this->rangeEnd);
            })->get();

        return view('admin.report.print.print-activity', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);
    }

    public function expenses()
    {
        $records = PaymentExpense::whereBetween('transaction_date', $this->range)->get();

        return view('admin.report.print.print-expenses', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);
    }

    public function payments()
    {
        $filterBy = request()->get('filter-by', 'all');
        $whereIn = $filterBy == 'all' ? ['pending', 'paid'] : [$filterBy];
        $records = Payment::whereBetween('due_date', $this->range)->whereIn('status', $whereIn)->get();

        return view('admin.report.print.print-payments', [
            'records' => $records,
            'filterBy' => $filterBy,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);
    }

    public function visitors()
    {
        $records = Visitor::whereBetween('time_in', $this->range)->get();

        return view('admin.report.print.print-visitors', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);
    }

    public function rfids()
    {
        $records = RfidMonitoring::with(['rfidData.vehicle.homeOwner'])->whereBetween('created_at', $this->range)->get();

        return view('admin.report.print.print-rfids', [
            'records' => $records,
            'range' => implode(' ~ ', $this->formattedRange())
        ]);
    }
}
