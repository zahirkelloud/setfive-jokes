<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $fillable = ['rating'];
    
    public function joke() {
        return $this->belongsTo(Joke::class, 'joke_id');
    }
}
