<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seat extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trip_id',
        'departure_city_id',
        'destination_city_id',
    ];
    

    //trip relation
    public function trip() : HasOne
    {
        return $this->hasOne(Trip::class);
    }
 

}
