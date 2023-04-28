<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{

    protected $fillable = [
        'event_id',
        'name',
        'photo',
        'bio',
        // add any other relevant information here
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
