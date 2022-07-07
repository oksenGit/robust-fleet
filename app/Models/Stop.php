<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stop extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trip_id',
        'city_id',
        'order'
    ];
    

    //trip relation
    public function trip() : HasOne
    {
        return $this->hasOne(Trip::class);
    }

    //city relation
    public function city() : HasOne
    {
        return $this->hasOne(City::class);
    }
}
