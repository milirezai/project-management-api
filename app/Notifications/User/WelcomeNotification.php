<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->delay = 20;
    }

    public int $timeout = 120;
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('register')
            ->greeting("hi $notifiable->fist_name ")
            ->line('Welcome to project management web app')
            ->line('Thank you for using our application!')
            ->action('github', url('https://github.com/milirezai/project-management-api'))
            ->salutation('milirezai');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'register',
            'message' => 'Welcome to project management web app',
        ];
    }

}
