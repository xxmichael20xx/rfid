<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentListExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $id = $item->id;
            $biller = $item->biller->last_full_name;
            $paymentType = $item->paymentType->type;
            $amount = number_format($item->amount, 2);
            $dueDate = Carbon::parse($item->due_date);
            $diffInDays = Carbon::now()->diffInDays($dueDate);
            $daysDue = $diffInDays <= 3 ? $diffInDays : 0;
            $status = ucfirst($item->status);
            $transactionDate = $item->transaction_date;
            $reference = $item->reference;

            // Return the processed data to export
            return compact('id', 'biller', 'paymentType', 'amount', 'dueDate', 'daysDue', 'status', 'transactionDate', 'reference');
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
            'Payment ID',
            'Biller',
            'Association Payment',
            'Amount',
            'Due Date',
            'Days Due',
            'Status',
            'Transaction Date',
            'Reference'
        ];
    }
}
