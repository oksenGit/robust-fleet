<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'seat_id',
        'departure_city_id',
        'destination_city_id',
    ];
    

    //user relation
    public function user() : HasOne
    {
        return $this->hasOne(User::class);
    }

    //seat relation
    public function seat() : HasOne
    {
        return $this->hasOne(Seat::class);
    }

       
    //departure city relation
    public function departureCity() : HasOne
    {
        return $this->hasOne(City::class, 'id', 'departure_city_id');
    }

    //destination city relation
    public function destinationCity() : HasOne
    {
        return $this->hasOne(City::class, 'id', 'destination_city_id');
    }
    
}
