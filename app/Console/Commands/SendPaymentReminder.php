<?php

namespace App\Console\Commands;

use App\Models\HomeOwner;
use App\Models\Notification;
use App\Models\Payment;
use App\Notifications\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:payment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders';

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
            $diffInDays = Carbon::now()->diffInDays($dueDate);

            if ($diffInDays <= 3 && $paymentData->status != 'paid') {
                $title = 'Payment Reminder';
                $amount = number_format($paymentData->amount, 2);
                $content = sprintf('Payment is due on `%s` with an amount of ₱`%s`.', $dueDate->format('M d, Y @ h:i A'), $amount);
    
                // Create new notification
                Notification::create([
                    'home_owner_id' => $billerId,
                    'title' => $title,
                    'content' => $content
                ]);
    
                $homeOwner->notify(new PaymentReminder($payment, $homeOwner));
            }

        }
        
        return Command::SUCCESS;
    }
}
