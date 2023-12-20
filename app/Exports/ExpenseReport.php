<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseReport implements FromCollection, WithHeadings
{
    protected $data;

    public $total;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $type = $item->type;
            $transactionDate = Carbon::parse($item->transaction_date)->format('M d, Y');
            $amount = '₱' . number_format($item->amount, 2);
            $this->total = $this->total + $item->amount;

            // Return the processed data to export
            return compact('type', 'transactionDate', 'amount');
        });

        $this->data[] = [
            '', '', 'Total: Php ' . $this->total
        ];
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
            'Transaction Date',
            'Amount',
        ];
    }
}
