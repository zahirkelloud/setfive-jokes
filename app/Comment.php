<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = ['username', 'comment'];
    
    public function joke() {
        return $this->belongsTo(Joke::class, 'joke_id');
    }
}
