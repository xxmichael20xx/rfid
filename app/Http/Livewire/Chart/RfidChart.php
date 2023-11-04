<?php

namespace App\Http\Livewire\Chart;

use App\Exports\ExportRfid;
use App\Models\RfidMonitoring;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class RfidChart extends Component
{
    
    public $dateFormat = 'm/d/Y';

    public $title;
    public $type;

    public $labels = [];
    public $rows = [];
    public $colors = [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(100, 100, 100, 0.7)'
    ];

    public $data;

    public $hasExport;

    public function mount($hasExport = false)
    {
        // initialize chart data
        $this->type = 'days';
        $this->setData();

        $this->hasExport = $hasExport;
    }

    public function change()
    {
        $this->setData();
        $this->emit('updateRfidhart', [
            'labels' => $this->labels,
            'datasets' => [[
                'label' => $this->title,
                'data' => $this->rows,
                'backgroundColor' => $this->colors
            ]],
        ]);
    }

    public function setData()
    {
        // reset the data
        $this->labels = [];
        $this->rows = [];
        $this->data = [];
        shuffle($this->colors);

        // check the type and set the data
        if ($this->type == 'days') {
            $this->setDays();
        } elseif ($this->type == 'weeks') {
            $this->setWeeks();
        } else {
            $this->setMonths();
        }
    }

    /**
     * Initialize the data as past `7 days`
     */
    public function setDays()
    {
        // Get the current date
        $currentDate = Carbon::now();

        // Loop through the past 7 days
        for ($i = 0; $i < 7; $i++) {
            // Calculate the start and end dates for each 1-week interval
            $startDate = $currentDate->copy()->subDays($i)->startOfDay();
            $endDate = $startDate->copy()->endOfDay();

            $startDateFormat = $startDate->copy()->format($this->dateFormat);
            $endDateFormat = $endDate->copy()->format($this->dateFormat);

            // Retrieve records for the current day within the date range
            $records = RfidMonitoring::whereBetween('date', [$startDateFormat, $endDateFormat])->get();

            $this->data = array_merge($this->data, $records->toArray());

            // Store the data in the array
            $this->labels[] = $startDate->format('M d, Y');
            $this->rows[] = $records->count();
        }

        $this->title = 'RFID Records this past 7 days';
    }

    /**
     * Initialize the data as past `4 weeks`
     */
    public function setWeeks()
    {
        // Get the current date
        $currentDate = Carbon::now();

        for ($i = 0; $i < 4; $i++) {
            // Calculate the start and end dates for each 1-week interval
            $startDate = $currentDate->copy()->subWeeks($i)->startOfDay();
            $endDate = $startDate->copy()->endOfWeek()->endOfDay();

            $startDateFormat = $startDate->copy()->format($this->dateFormat);
            $endDateFormat = $endDate->copy()->format($this->dateFormat);

            // Retrieve records for the current 1-week interval
            $records = RfidMonitoring::whereBetween('date', [$startDateFormat, $endDateFormat])->get();

            $this->data = array_merge($this->data, $records->toArray());

            // Store the data in the array
            $this->labels[] = $startDate->format('M d') . ' - ' . $endDate->format('M d');
            $this->rows[] = $records->count();
        }

        $this->title = 'RFID Records this past 4 weeks';
    }

    /**
     * Initialize the data as past `4 months`
     */
    public function setMonths()
    {
        // Get the current date
        $currentDate = Carbon::now();

        for ($i = 0; $i < 4; $i++) {
            // Calculate the start and end dates for each month
            $startDate = $currentDate->copy()->subMonths($i)->startOfMonth()->startOfDay();
            $endDate = $currentDate->copy()->subMonths($i)->endOfMonth()->endOfDay();

            $startDateFormat = $startDate->copy()->format($this->dateFormat);
            $endDateFormat = $endDate->copy()->format($this->dateFormat);

            // Retrieve records for the current month
            $records = RfidMonitoring::whereBetween('date', [$startDateFormat, $endDateFormat])->get();

            $this->data = array_merge($this->data, $records->toArray());

            // Store the data in the array
            $this->labels[] = $startDate->format('M Y');
            $this->rows[] = $records->count();
        }

        $this->title = 'RFID Records this past 4 months';
    }

    public function exportData()
    {
        $timestamp = now()->format('Y-m-d_Hi'); // Current timestamp in the format: yyyy-mm-dd_HHmm
        $filename = 'rfid_monitoring_' . $timestamp . '_' . Str::replace(' ', '_', $this->title) . '.xlsx';

        return Excel::download(new ExportRfid($this->data), $filename);
    }

    public function render()
    {
        return view('livewire.Chart.rfid-chart');
    }
}
