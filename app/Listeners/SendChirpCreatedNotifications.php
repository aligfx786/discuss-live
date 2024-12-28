<?php

namespace App\Listeners;

use App\Events\ChirpCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\NewChirp;

class SendChirpCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChirpCreated $event): void
    {
        // We've marked our listener with the ShouldQueue interface, which tells Laravel that the listener should be run in a queue. By default, the "database" queue will be used to process jobs asynchronously. To begin processing queued jobs, you should run the php artisan queue:work Artisan command in your terminal.
        // We've also configured our listener to send notifications to every user in the platform, except the author of the Chirp. In reality, this might annoy users, so you may want to implement a "following" feature so users only receive notifications for accounts they follow.
        // We've used a database cursor to avoid loading every user into memory at once.

        // In a production application we should add the ability for our users to unsubscribe from notifications like these.

        foreach (User::whereNot('id', $event->chirp->user_id)->cursor() as $user) {
            $user->notify(new NewChirp($event->chirp));
        }
    }
}
