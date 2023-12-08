<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RfidReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $rfid = $item->rfid;
            $homeOwner = $item->rfidData->vehicle->homeOwner->last_full_name;
            $date = $item->date;
            $timeInOut = $item->time_in . ' | ' . $item->time_out;

            // Return the processed data to export
            return compact('rfid', 'homeOwner', 'date', 'timeInOut');
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
            'Home Owner Id',
            'Name',
            'Date',
            'Time In | Out',
        ];
    }
}
