<?php

namespace App\Services;

use App\Models\City;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Trip;
use Carbon\Carbon;
use Exception;

class TripService
{

  public function createTripSeats($trip_id, $number)
  {
    $seats = [];
    $now = Carbon::now();
    for ($i = 0; $i < $number; $i++) {
      //add new seat to seats
      $seats[] = [
        'trip_id' => $trip_id,
        'created_at' => $now,
        'updated_at' => $now,
      ];
    }

    //insert seats to database
    Seat::insert($seats);
  }

  public function availableSeats($departureCityId, $destinationCityId)
  {
    $availableSeats = [];
    //get trips where has stop in departure city and a stop in destination city.
    $trips = $this->getTripsContainingStops($departureCityId, $destinationCityId);
    //for each trip
    foreach ($trips as $trip) {
      //check if departure stop is before destination stop in the trip.
      if (!$this->isDepartureBeforeDestination($trip, $departureCityId, $destinationCityId)) {
        continue;
      }
      //get available seats for trip based on departure stop and destination stop.
      $tripAvailableSeats = $this->getTripAvailableSeats($trip, $departureCityId, $destinationCityId);
      if (empty($tripAvailableSeats)) {
        continue;
      }
      $availableSeats = array_merge($availableSeats, $tripAvailableSeats);
    }

    return $availableSeats;
  }

  public function bookSeat($seat_id, $user_id,$departureCityId, $destinationCityId)
  {
    $departureCity = City::find($departureCityId);
    $destinationCity = City::find($destinationCityId);
    
    if (!$this->selectedStopsAreValid($seat_id, $departureCityId, $destinationCityId)) {
      throw new Exception(__('app.error_stop_not_belong_to_trip'), 406);
    }

    if (!$this->isSeatAvailable($seat_id, $departureCity, $destinationCity)) {
      throw new Exception(__('app.error_seat_already_booked'), 409);
    }


    $reservation = Reservation::create([
      'seat_id' => $seat_id,
      'user_id' => $user_id,
      'departure_city_id' => $departureCityId,
      'destination_city_id' => $destinationCityId,
    ]);
    return $reservation;
  }

  private function getTripsContainingStops(int $departureCityId, int $destinationCityId)
  {
    $trips = Trip::whereHas('stops', function ($query) use ($departureCityId) {
      $query->where('city_id', $departureCityId);
    })->whereHas('stops', function ($query) use ($destinationCityId) {
      $query->where('city_id', $destinationCityId);
    })->get();
    return $trips;
  }

  private function isDepartureBeforeDestination(Trip $trip, int $departureCityId, int $destinationCityId): bool
  {
    $departureStop = $trip->stops->where('city_id', $departureCityId)->first();
    $destinationStop = $trip->stops->where('city_id', $destinationCityId)->first();

    return $departureStop->order < $destinationStop->order;
  }

  private function getTripAvailableSeats(Trip $trip, int $departureCityId, int $destinationCityId)
  {
    //get trip seats without created_at and updated_at.
    $tripSeats = $trip->seats->map(function ($seat) {
      return $seat->only(['id', 'trip_id']);
    });
    $availableSeats = [];
    $departureCity = City::find($departureCityId);
    $destinationCity = City::find($destinationCityId);

    foreach ($tripSeats as $tripSeat) {
      if (!$this->isSeatAvailable($tripSeat['id'], $departureCity, $destinationCity)) {
        continue;
      }
      $availableSeats[] = $tripSeat;
    }

    return $availableSeats;
  }

  private function isSeatAvailable(int $seatID, City $departureCity, City $destinationCity): bool
  {
    $reservation = Reservation::where('seat_id', $seatID)->first();
    if (!$reservation) {
      return true;
    }
    return $this->reservedSeatWillBeAvailable($reservation, $departureCity, $destinationCity);
  }

  private function reservedSeatWillBeAvailable($reservation, $departureCity, $destinationCity): bool
  {
    $departureStopOrder = $this->getReservationStopOrder($reservation, $departureCity->id);
    $destinationStopOrder = $this->getReservationStopOrder($reservation, $destinationCity->id);
    $reservationDestinationStopOrder = $this->getReservationStopOrder($reservation, $reservation->destination_city_id);
    $reservationDepartureStopOrder = $this->getReservationStopOrder($reservation, $reservation->departure_city_id);

    //if trip reservation destination city is at or before departure city
    //or  // if destination city is before trip reservation departure city
    return $reservationDestinationStopOrder <= $departureStopOrder ||
      $destinationStopOrder <= $reservationDepartureStopOrder;
  }

  private function getReservationStopOrder($reservation, $cityID)
  {
    $stop = $reservation->seat->trip->stops->where('city_id', $cityID)->first();
    return $stop->order;
  }

  private function selectedStopsAreValid($seat_id, $departureCityID, $destinationCityID)
  {
    $seat = Seat::find($seat_id);
    $trip = Trip::find($seat->trip_id);
    $departureStop = $trip->stops->where('city_id', $departureCityID)->first();
    $destinationStop = $trip->stops->where('city_id', $destinationCityID)->first();
    if ($departureStop == null ||  $destinationStop == null) {
      return false;
    }
    return  $departureStop->order < $destinationStop->order;
  }
}
