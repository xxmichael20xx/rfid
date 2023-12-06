<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Set the data to export
        $this->data = collect($data)->map(function($item) {
            $biller = $item->biller->last_full_name;
            $blockLotItem = $item->block_lot_item;
            $paymentType = $item->paymentType->type;
            $amount = '₱' . number_format($item->amount, 2);
            $reference = $item->reference ?? '';
            $dueDate = Carbon::parse($item->due_date)->format('M d, Y');
            $status = str($item->status)->title();
            $datePaid = $item->status == 'paid' ? Carbon::parse($item->date_paid)->format('M d, Y @ h:ia') : '';

            // Return the processed data to export
            return compact('biller', 'blockLotItem', 'paymentType', 'amount', 'reference', 'dueDate', 'status', 'datePaid');
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
            'Biller',
            'Block & Lot',
            'Payment Type',
            'Amount',
            'Reference',
            'Due Date',
            'Status',
            'Date Paid',
        ];
    }
}
