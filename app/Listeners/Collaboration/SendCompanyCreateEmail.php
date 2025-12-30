<?php

namespace App\Listeners\Collaboration;

use App\Events\Collaboration\CompanyCreate;
use App\Notifications\Collaboration\CompanyCreateNotification;
use App\Notifications\User\UserSyncRoleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendCompanyCreateEmail implements ShouldQueue
{
//    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(CompanyCreate $event): void
    {
        $event->user->notify(new CompanyCreateNotification());
        $event->user->notify(new UserSyncRoleNotification());
    }
}
