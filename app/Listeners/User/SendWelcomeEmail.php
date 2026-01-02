<?php

namespace App\Listeners\User;

use App\Events\User\UserRegistered;
use App\Models\User\User;
use App\Notifications\User\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {

    }

    public function handle(UserRegistered $event): void
    {
        User::find($event->user)
            ->notify(new WelcomeNotification());
     }
}
