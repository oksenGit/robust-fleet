<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function trip() : BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    //city relation
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
