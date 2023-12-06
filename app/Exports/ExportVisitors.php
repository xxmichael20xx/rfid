<?php

namespace App\Exports;

use App\Models\HomeOwner;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportVisitors implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $homeOwner = HomeOwner::withTrashed()->where('id', data_get($item, 'home_owner_id'))->first();
            $for = $homeOwner->last_full_name ?? 'N/A';
            $visitor = data_get($item, 'last_full_name');
            $dateVisited = Carbon::parse(data_get($item, 'time_in'))->format('M d, Y @ h:ia');

            // Return the processed data to export
            return compact('for', 'visitor', 'dateVisited');
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
            'Date Visited',
        ];
    }
}
