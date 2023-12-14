<?php

namespace App\Exports;

use App\Models\User;
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
            $amount = number_format($item->amount, 2);
            $_dueDate = Carbon::parse($item->due_date);
            $dueDate = $_dueDate->copy()->format('M d, Y @ h:i A');
            $diffInDays = Carbon::now()->diffInDays($_dueDate);
            $daysDue = $diffInDays <= 3 ? $diffInDays : 0;
            $status = ucfirst($item->status);
            $transactionDate = $item->transaction_date;
            $reference = $item->reference;
            $receivedBy = $item->payment_received_by;
            $datePaid = $item->payment_received_by !== 'N/A' ? Carbon::parse($item->date_paid)->format('M d, Y @ h:i A') : '';

            // Return the processed data to export
            return compact('id', 'biller', 'amount', 'dueDate', 'daysDue', 'status', 'transactionDate', 'reference', 'receivedBy', 'datePaid');
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
            'Amount',
            'Due Date',
            'Days Due',
            'Status',
            'Transaction Date',
            'Reference',
            'Received By',
            'Received Date'
        ];
    }
}
