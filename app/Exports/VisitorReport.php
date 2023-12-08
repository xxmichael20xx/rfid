<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitorReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $visitor = $item->last_full_name;
            $for = $item->for->last_full_name;
            $timeIn = Carbon::parse($item->time_in)->format('M d, Y @ h:ia');
            $timeOut = Carbon::parse($item->time_out)->format('M d, Y @ h:ia');
            $notes = $item->notes;

            // Return the processed data to export
            return compact('visitor', 'for', 'timeIn', 'timeOut', 'notes');
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
            'Visitor',
            'For',
            'Entry date',
            'Exit date',
            'Notes'
        ];
    }
}
