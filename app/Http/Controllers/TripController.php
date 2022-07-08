<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailableSeatsRequest;
use App\Http\Requests\BookSeatRequest;
use App\Services\TripService;

class TripController extends Controller
{
    private $tripService;

    public function __construct()
    {
        $this->tripService = new TripService();
    }

    //list of available seats for departure and destination city.
    public function availableSeats(AvailableSeatsRequest $request)
    {
        $departureCityId = $request->input('departure_city_id');
        $destinationCityId = $request->input('destination_city_id');
        return $this->tripService->availableSeats($departureCityId, $destinationCityId);
    }

    //book seat for trip.
    public function bookSeat(BookSeatRequest $request){
        $seat_id = $request->input('seat_id');
        $departureCityId = $request->input('departure_city_id');
        $destinationCityId = $request->input('destination_city_id');
        return $this->tripService->bookSeat($seat_id, $departureCityId, $destinationCityId);
    }
}
