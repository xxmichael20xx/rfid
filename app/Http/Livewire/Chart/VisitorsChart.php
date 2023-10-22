<?php

namespace App\Http\Livewire\Chart;

use Carbon\Carbon;
use Livewire\Component;

class VisitorsChart extends Component
{
    public $title;
    public $type;

    public $labels = [];
    public $rows = [];
    public $colors = [];

    public function mount()
    {
        // initialize chart data
        $this->type = 'days';
        $this->setData();
    }

    public function change()
    {
        $this->setData();
        $this->emit('updateChart', [
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
        // Loop through the past 7 days
        for ($i = 0; $i < 7; $i++) {
            // Calculate the start and end dates for each day's range
            $endDate = Carbon::now()->subDays($i);

            // Retrieve records for the current day within the date range
            // $records = Activity::whereDate('created_at', $endDate)->count();
            $records = rand(4, 23);

            // Store the data in the array
            $this->labels[] = $endDate->format('M d, Y');
            $this->rows[] = $records;
        }

        $this->title = 'Visitors this past 7 days';
        $this->colors = [
            'rgba(255, 99, 132, 0.7)',  // Red
            'rgba(54, 162, 235, 0.7)',  // Blue
            'rgba(255, 206, 86, 0.7)',  // Yellow
            'rgba(75, 192, 192, 0.7)',  // Green
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 159, 64, 0.7)',  // Orange
            'rgba(100, 100, 100, 0.7)'  // Gray
        ];
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
            $startDate = $currentDate->copy()->subWeeks($i);
            $endDate = $startDate->copy()->endOfWeek();

            // Retrieve records for the current 1-week interval
            // $records = Activity::whereBetween('created_at', [$startDate, $endDate])->count();
            $records = rand(10, 50);

            // Store the data in the array
            $this->labels[] = $startDate->format('M d') . ' - ' . $endDate->format('M d');
            $this->rows[] = $records;
        }

        $this->title = 'Visitors this past 4 weeks';
        $this->colors = [
            'rgba(54, 162, 235, 0.7)', // Blue
            'rgba(255, 206, 86, 0.7)', // Yellow
            'rgba(255, 159, 64, 0.7)', // Orange
            'rgba(100, 100, 100, 0.7)' // Gray
        ];
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
            $startDate = $currentDate->copy()->subMonths($i)->startOfMonth();
            $endDate = $currentDate->copy()->subMonths($i)->endOfMonth();

            // Retrieve records for the current month
            // $records = Activity::whereBetween('created_at', [$startDate, $endDate])->count();
            $records = rand(40, 70);

            // Store the data in the array
            $this->labels[] = $startDate->format('M Y');
            $this->rows[] = $records;
        }

        $this->title = 'Visitors this past 4 months';
        $this->colors = [
            'rgba(255, 99, 132, 0.7)',  // Red
            'rgba(75, 192, 192, 0.7)',  // Teal
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 159, 64, 0.7)',  // Orange
            'rgba(0, 128, 0, 0.7)',     // Green
            'rgba(0, 0, 255, 0.7)',     // Navy
            'rgba(0, 255, 0, 0.7)'      // Lime
        ];
    }

    public function render()
    {
        return view('livewire.Chart.visitors-chart');
    }
}
