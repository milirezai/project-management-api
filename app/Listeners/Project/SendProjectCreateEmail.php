<?php

namespace App\Listeners\Project;

use App\Events\Project\CreateProject;
use App\Notifications\Project\ProjectCreateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendProjectCreateEmail // implements ShouldQueue
{
    //use InteractsWithQueue;
    public function __construct()
    {
        //
    }

    public function handle(CreateProject $event): void
    {
       $event->companyOwner->notify(new ProjectCreateNotification());
       $event->projectCreator->notify(new ProjectCreateNotification());
     Notification::send(
         $event->projectMembers,
         new ProjectCreateNotification()
     );
    }
}
