<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $title = $item->title;
            $location = $item->location;
            $description = $item->description;
            $activityDate = match($item->start_date === $item->end_date) {
                true => Carbon::parse($item->start_date)->format('M d, Y'),
                default => Carbon::parse($item->start_date)->format('M d') . ' - ' . Carbon::parse($item->end_date)->format('M d, Y')
            };
            $startTime = Carbon::parse($item->start_time)->format('h:ia');

            // Return the processed data to export
            return compact('title', 'location', 'description', 'activityDate', 'startTime');
        });
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Set the heading of the exported report
     */
    public function headings(): array
    {
        return [
            'Title',
            'Location',
            'Description',
            'Activity Date',
            'Start Time'
        ];
    }
}
