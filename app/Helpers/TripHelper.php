<?php

namespace App\Helpers;

use App\Models\Seat;
use Carbon\Carbon;

class TripHelper{

    //bulk create n seat for a trip
    public function createSeats($trip_id, $number){
        $seats = [];    
        $now = Carbon::now();
        for($i = 0; $i < $number; $i++){
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
}