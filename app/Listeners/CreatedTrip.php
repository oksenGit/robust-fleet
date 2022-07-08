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
        $NUMBER_OF_SEATS = config('values.NUMBER_OF_SEATS');
        $tripService =  new TripService();
        $tripService->createTripSeats($trip->id, $NUMBER_OF_SEATS);
    }
}
