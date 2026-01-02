<?php

namespace App\Notifications\Collaboration;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct( )
    {
        $this->delay = now()->addSecond(20);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'company update',
            'message' => 'Your company has been successfully updated. Please visit your companys settings to view the changes.'
        ];
    }
}
