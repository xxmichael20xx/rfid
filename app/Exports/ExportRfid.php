<?php

namespace App\Exports;

use App\Models\HomeOwner;
use App\Models\HomeOwnerVehicle;
use App\Models\Rfid;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRfid implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $rfid = data_get($item, 'rfid');

            // get the rfid data
            $rfidData = Rfid::withTrashed()->where('rfid', $rfid)->first();

            // get the vehicle data
            $vehicle = HomeOwnerVehicle::where('id', $rfidData->vehicle_id)->first();

            // get the homeOwner data
            $homeOwner = HomeOwner::withTrashed()->where('id', $vehicle->home_owner_id)->first();

            return [
                'rfid' => $rfid,
                'home_owner' => $homeOwner->last_full_name,
                'date' => data_get($item, 'date'),
                'time_in' => data_get($item, 'time_in'),
                'time_out' => data_get($item, 'time_out'),
            ];
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
            'RFID',
            'Home Owner',
            'Date',
            'Time In',
            'Time Out',
        ];
    }
}
