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
        $this->type = 'weeks'; // Set the default type to weeks
        $this->setData();
        $this->setColors();
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
        if ($this->type == 'weeks') {
            $this->setWeeks();
        } elseif ($this->type == 'months') {
            $this->setMonths();
        } elseif ($this->type == 'years') {
            $this->setYears();
        }

        // Reverse the arrays
        $this->labels = array_reverse($this->labels);
        $this->rows = array_reverse($this->rows);
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
    }

    /**
     * Initialize the data as past `12 months`
     */
    public function setMonths()
    {
        for ($i = 0; $i < 12; $i++) {
            // Get the current date
            $currentDate = Carbon::now()->subMonths($i);

            // Calculate the start and end dates for each month
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();

            // Retrieve records for the current month
            $records = Activity::where('start_date', '>=', $startDate)
                ->where('end_date', '<=', $endDate)
                ->count();

            // Store the data in the array
            $this->labels[] = $startDate->format('M Y');
            $this->rows[] = $records;
        }

        $this->title = 'Activities per month';
    }

    /**
     * Initialize the data as past `4 years`
     */
    public function setYears()
    {
        for ($i = 0; $i < 4; $i++) {
            // Get the current date
            $currentDate = Carbon::now()->subYears($i);

            // Calculate the start and end dates for each year
            $startDate = $currentDate->copy()->startOfYear();
            $endDate = $currentDate->copy()->endOfYear();

            // Retrieve records for the current year
            $records = Activity::where('start_date', '>=', $startDate)
                ->where('end_date', '<=', $endDate)
                ->count();

            // Store the data in the array
            $this->labels[] = $startDate->format('Y');
            $this->rows[] = $records;
        }

        $this->title = 'Activities this past 4 years';
    }

    /**
     * Set the colors for the datasets
     */
    public function setColors()
    {
        // Set colors for up to 12 datasets
        $this->colors = [
            'rgba(255, 99, 132, 0.7)',  // Red
            'rgba(54, 162, 235, 0.7)',  // Blue
            'rgba(255, 206, 86, 0.7)',  // Yellow
            'rgba(75, 192, 192, 0.7)',  // Green
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 159, 64, 0.7)',  // Orange
            'rgba(100, 100, 100, 0.7)', // Gray
            'rgba(0, 0, 255, 0.7)',     // Navy
            'rgba(0, 255, 0, 0.7)',     // Lime
            'rgba(255, 0, 255, 0.7)',   // Magenta
            'rgba(255, 255, 0, 0.7)',   // Yellow
            'rgba(0, 255, 255, 0.7)',   // Cyan
        ];
    }

    public function render()
    {
        return view('livewire.Chart.activity-chart');
    }
}
