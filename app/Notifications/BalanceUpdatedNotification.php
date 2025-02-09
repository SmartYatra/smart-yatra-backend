<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class BalanceUpdatedNotification extends Notification
{
    use Queueable;

    protected $balance;

    public function __construct($balance)
    {
        $this->balance = $balance;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Database and mail notification
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'balance' => $this->balance,
            'message' => 'Your wallet balance has been updated.',
        ];
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
            ->subject('Wallet Balance Updated')
            ->line('Your wallet balance has been updated to: ' . $this->balance)
            ->action('Check Wallet', url('/wallet'))
            ->line('Thank you for using our service!');
    }
}
