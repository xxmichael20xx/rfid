<?php

namespace App\Http\Livewire\Chart;

use Carbon\Carbon;
use Livewire\Component;

class TotalResidenceChart extends Component
{
    public $title;

    public $labels = [];
    public $rows = [];
    public $colors = [];

    public function mount()
    {
        // initialize chart data
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

    /**
     * Set up the data for the bar chat
     */
    public function setData()
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        for ($i = 0; $i < 5; $i++) {
            // Calculate the start and end dates for the current year
            $startDate = Carbon::createFromDate($currentYear - $i, 1, 1);
            $endDate = $startDate->copy()->endOfYear();

            // Retrieve records for the current year
            // $records = HomeOwner::whereYear('created_at', '=', $currentYear - $i)
            //     ->whereBetween('created_at', [$startDate, $endDate])
            //     ->count();
            $records = $currentYear - ($i * rand(350, 400));

            // Store the data in the array
            $this->labels[] = $startDate->format('Y');
            $this->rows[] = $records;
        }

        $this->labels = array_reverse($this->labels);
        $this->rows = array_reverse($this->rows);
        $this->title = 'Residence in Subdivision';
        $this->colors = [
            'rgba(75, 192, 192, 0.7)',  // Green
        ];
    }

    public function render()
    {
        return view('livewire.Chart.total-residence-chart');
    }
}
