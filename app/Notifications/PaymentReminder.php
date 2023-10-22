<?php

namespace App\Notifications;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PaymentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    public $homeOwner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payment, $homeOwner)
    {
        $this->homeOwner = $homeOwner;
        $this->payment = Payment::with('paymentType')->find($payment['id']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $dueDate = Carbon::parse($this->payment->due_date);
        $diffInDays = Carbon::now()->diffInDays($dueDate);

        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('We hope this email finds you well. This is a friendly reminder regarding an upcoming payment that is due. Please take a moment to review the details below:')
            ->line(new HtmlString('This is a reminder that your payment is <strong>due in ' . $diffInDays . ' day(s).</strong>'))
            ->line(new HtmlString('<strong>Description: ' . $this->payment->paymentType->type . '</strong>'))
            ->line(new HtmlString('<strong>Due Date: ' . $dueDate->format('M d, Y') . '</strong>'))
            ->line(new HtmlString('<strong>Due Amount: ₱' . number_format($this->payment->amount, 2) . '</strong>'))
            ->line('To ensure that your account remains in good standing, we kindly request that you make the payment as soon as possible.')
            ->line('Thank you for your prompt attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
