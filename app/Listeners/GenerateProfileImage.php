<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

// Models
use App\Models\ProfileImage;

class GenerateProfileImage
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
        // Send off request to get user avatar based on name
        $response = Http::withOptions([
            'verify' => false
        ])->get('https://eu.ui-avatars.com/api/?name='.str_replace(' ', '+', $event->user->name));

        $img = '';

        // Request sucessful
        if ($response->getStatusCode() == '200') {
            $img = 'data:image/png;base64,'.base64_encode($response->body());
        }

        // Create new profile image
        ProfileImage::create([
            'user_id' => $event->user->id,
            'img' => $img
        ]);
    }
}
