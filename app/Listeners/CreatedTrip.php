<?php

namespace App\Listeners;

use App\Helpers\TripHelper;

class CreatedTrip
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($trip)
    {
        $NUMBER_OF_SEATS = 12;
        $tripHelper =  new TripHelper();
        $tripHelper->createSeats($trip->id, $NUMBER_OF_SEATS);
    }
}
