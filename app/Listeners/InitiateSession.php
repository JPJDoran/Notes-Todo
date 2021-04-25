<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// Models
use App\Models\User;

class InitiateSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (is_null($user = User::find($event->user->id))) {
            return;
        }

        if (is_null($profileImage = $user->ProfileImage)) {
            return;
        }

        session([
            'profile_img' => $profileImage->img
        ]);
    }
}
