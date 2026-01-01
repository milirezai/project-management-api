<?php

namespace App\Listeners\Project;

use App\Events\Project\CreateProject;
use App\Models\User\User;
use App\Notifications\Project\ProjectCreateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendProjectCreateEmail  implements ShouldQueue
{
    use InteractsWithQueue;
    public function __construct()
    {
        //
    }

    public function handle(CreateProject $event): void
    {
        $users = array_unique(array_values( array_merge($event->users[0],[$event->users[1],$event->users[2]])));
        $users = User::findMany($users);
        Notification::send(
            $users,
            new ProjectCreateNotification()
        );
    }
}
