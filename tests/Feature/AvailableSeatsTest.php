<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\City;
use App\Models\Reservation;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableSeatsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_route_parameters()
    {
        $response = $this->get('/api/availableSeats');
        $response->assertInvalid();

        $response = $this->get('/api/availableSeats?departure_city_id=&destination_city_id=');
        $response->assertInvalid();

        $response = $this->get('/api/availableSeats?departure_city_id=1');
        $response->assertInvalid();


        $response = $this->get('/api/availableSeats?destination_city_id=1');
        $response->assertInvalid();
    }

    public function test_no_trips()
    {
        $cities = City::factory()->count(2)->create();
        $response = $this->get('/api/availableSeats?departure_city_id='.$cities[0]->id.'&destination_city_id='.$cities[1]->id);
        $response->assertOk();
        $jsonBody = $response->json();
        //assert that the response is an array
        $this->assertIsArray($jsonBody);
        //assert array is empty
        $this->assertEmpty($jsonBody);
        //delete cities
        $cities[0]->delete();
        $cities[1]->delete();
    }

    public function test_has_trips_and_seats()
    {
        $cities = City::factory()->count(3)->create();
        $bus = Bus::factory()->create();
        $trip = Trip::create([
            'bus_id' => $bus->id,
        ]);
        //create a stop for each city
        for($i=0; $i<3; $i++){
            Stop::create([
                'trip_id' => $trip->id,
                'city_id' => $cities[$i]->id,
                'order' => $i,
            ]);
        }
        $response = $this->get('/api/availableSeats?departure_city_id='.$cities[0]->id.'&destination_city_id='.$cities[1]->id);
        $response->assertOk();
        $jsonBody = $response->json();
        //assert that the response is an array
        $this->assertIsArray($jsonBody);
        //assert array is empty
        $this->assertNotEmpty($jsonBody);
        //assert Json Structure
        $response->assertJsonStructure([
            '*' => ['id','trip_id']
        ], $jsonBody);

        $seatsCountBeforeReservation = count($jsonBody);

        //validate that a reservation from city[1] to city[2] won't affect the available seats for city[0] to city[1]
        Reservation::create([
            'trip_id' => $trip->id,
            'seat_id' => $trip->seats[0]->id,
            'departure_city_id' => $cities[1]->id,
            'destination_city_id' => $cities[2]->id,
        ]);
        $response = $this->get('/api/availableSeats?departure_city_id='.$cities[0]->id.'&destination_city_id='.$cities[1]->id);
        $seatsCountAfterReservation = count($response->json());
        $this->assertEquals($seatsCountBeforeReservation, $seatsCountAfterReservation);

        //validate that a reservation from city[0] to city[1] will affect the available seats for city[0] to city[1]
        Reservation::create([
            'trip_id' => $trip->id,
            'seat_id' => $trip->seats[1]->id,
            'departure_city_id' => $cities[0]->id,
            'destination_city_id' => $cities[1]->id,
        ]);
        $response = $this->get('/api/availableSeats?departure_city_id='.$cities[0]->id.'&destination_city_id='.$cities[1]->id);
        $seatsCountAfterReservation = count($response->json());
        $this->assertEquals($seatsCountBeforeReservation-1, $seatsCountAfterReservation);
    }

    public function test_has_trips_no_available_seats()
    {
        $cities = City::factory()->count(3)->create();
        $bus = Bus::factory()->create();
        $trip = Trip::create([
            'bus_id' => $bus->id,
        ]);
        //create a stop for each city
        for($i=0; $i<3; $i++){
            Stop::create([
                'trip_id' => $trip->id,
                'city_id' => $cities[$i]->id,
                'order' => $i,
            ]);
        }
        $seats = $trip->seats;
        //reserve all seats
        foreach($seats as $seat){
            Reservation::create([
                'seat_id' => $seat->id,
                'departure_city_id' => $cities[0]->id,
                'destination_city_id' => $cities[2]->id,
            ]);
        }
        $response = $this->get('/api/availableSeats?departure_city_id='.$cities[0]->id.'&destination_city_id='.$cities[1]->id);
        $response->assertOk();
        $jsonBody = $response->json();
        //assert that the response is an array
        $this->assertIsArray($jsonBody);
        //assert array is empty
        $this->assertEmpty($jsonBody);
    }
}
