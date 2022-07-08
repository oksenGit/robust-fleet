<?php

namespace App\Listeners;

use App\Services\TripService;

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
        $tripService =  new TripService();
        $tripService->createTripSeats($trip->id, $NUMBER_OF_SEATS);
    }
}
