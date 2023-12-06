<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $type = $item->type;
            $amount = '₱' . number_format($item->amount, 2);
            $transactionDate = Carbon::parse($item->transaction_date)->format('M d, Y');

            // Return the processed data to export
            return compact('type', 'amount', 'transactionDate');
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
            'Type',
            'Amount',
            'Transaction Date',
        ];
    }
}
