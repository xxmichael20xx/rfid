<?php

namespace App\Http\Livewire\Chart;

use App\Models\Activity;
use Carbon\Carbon;
use Livewire\Component;

class ActivityChart extends Component
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
        $this->emit('updateActivityChart', [
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

        // Reverse the arrays
        $this->labels = array_reverse($this->labels);
        $this->rows = array_reverse($this->rows);
    }

    /**
     * Initialize the data as past `7 days`
     */
    public function setDays()
    {
        // Loop through the past 7 days
        for ($i = 0; $i < 7; $i++) {
            // Calculate the start and end dates for each day's range
            $today = Carbon::now()->subDays($i);
            $todayFormat = $today->copy()->format('Y-m-d');

            // Retrieve records for the current day within the date range
            $records = Activity::whereDate('start_date', $todayFormat)
                ->orWhereDate('end_date', $todayFormat)
                ->orWhere(function($query) use($todayFormat) {
                    $query->where('start_date', '<=', $todayFormat)
                        ->where('end_date', '>=', $todayFormat);
                })
                ->count();

            // Store the data in the array
            $this->labels[] = $todayFormat;
            $this->rows[] = $records;
        }

        $this->title = 'Activities this past 7 days';
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
        for ($i = 0; $i < 4; $i++) {
            // Get the current date
            $currentDate = Carbon::now()->subWeeks($i);

            // Calculate the start and end dates for each 1-week interval
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();

            $startDateFormat = $startDate->copy()->format('Y-m-d');
            $endDateFormat = $endDate->copy()->format('Y-m-d');

            // Retrieve records for the current 1-week interval
            $records = Activity::where('start_date', $startDateFormat)
                ->orWhere('end_date', $endDateFormat)
                ->orWhere(function($query) use($startDateFormat, $endDateFormat) {
                    $query->where('start_date', '>=', $startDateFormat)
                        ->where('end_date', '<=', $endDateFormat);
                })
                ->count();

            // Store the data in the array
            $this->labels[] = $startDate->format('M d') . ' - ' . $endDate->format('M d');
            $this->rows[] = $records;
        }

        $this->title = 'Activities this past 4 weeks';
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
            $records = Activity::where('start_date', $startDate)
                ->orWhere('end_date', $endDate)
                ->orWhere(function($query) use($startDate, $endDate) {
                    $query->where('start_date', '>=', $startDate)
                        ->where('end_date', '<=', $endDate);
                })
                ->count();

            // Store the data in the array
            $this->labels[] = $startDate->format('M Y');
            $this->rows[] = $records;
        }

        $this->title = 'Activities this past 4 months';
        $this->colors = [
            'rgba(255, 99, 132, 0.7)',  // Red
            'rgba(75, 192, 192, 0.7)',  // Teal
            'rgba(0, 0, 255, 0.7)',     // Navy
            'rgba(0, 255, 0, 0.7)'      // Lime
        ];
    }

    public function render()
    {
        return view('livewire.Chart.activity-chart');
    }
}
