<?php

namespace App\Console\Commands;

use App\Models\HomeOwner;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DuePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and create payments on due';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = Payment::all();

        foreach ($payments as $payment) {
            $billerId = data_get($payment, 'home_owner_id');
            $homeOwner = HomeOwner::find($billerId);

            if (! $homeOwner) {
                continue;
            }

            $paymentData = Payment::find(data_get($payment, 'id'));
            $dueDate = Carbon::parse($paymentData->due_date);

            if (Carbon::now()->greaterThan($dueDate)) {
                $paymentData->update([
                    'status' => 'failed'
                ]);

                $newDueDate = $dueDate->addMonthsNoOverflow();
                $newPaymentData = $payment->toArray();
                $modifiedData = [
                    'amount' => data_get($newPaymentData, 'amount') * 2,
                    'mode' => 'Cash',
                    'transaction_date' => null,
                    'date_paid' => null,
                    'due_date' => $newDueDate->toDateTimeString(),
                    'reference' => null,
                    'status' => 'pending',
                    'received_by' => null
                ];

                // Iterate through the update array and overwrite values in the original array
                foreach ($modifiedData as $key => $value) {
                    if (array_key_exists($key, $newPaymentData)) {
                        $newPaymentData[$key] = $value;
                    }
                }

                // Create new recurring payment
                Payment::create($newPaymentData);
            }
        }

        return Command::SUCCESS;
    }
}
