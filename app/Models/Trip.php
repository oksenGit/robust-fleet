<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trip extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bus_id',
    ];
    

    //Stops relation
    public function stops() : HasMany
    {
        return $this->hasMany(Stop::class);
    }

    //Bus relation
    public function bus() : HasOne
    {
        return $this->hasOne(Bus::class);
    }

    //Seats relation
    public function seats() : HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
