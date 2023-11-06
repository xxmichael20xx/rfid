<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class UserAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rawPassword;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($rawPassword)
    {
        $this->rawPassword = $rawPassword;
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
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('We hope this email finds you well. This is a friendly of user account created for Glen Ville. Please save the your credentials below for your login to the mobile application:')
            ->line(new HtmlString('<strong>Username: '. $notifiable->email .'<strong>'))
            ->line(new HtmlString('<strong>Password: '. $this->rawPassword .'<strong>'))
            ->line(new HtmlString('Note: Please never share youg account.'));
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
