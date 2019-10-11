<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    protected $fillable = ['id', 'joke'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $appends = ['average_rating'];
    public $hidden = ['ratings'];

    public function ratings() {
        return $this->hasMany(Rating::class, 'joke_id');
    }

    public function getAverageRatingAttribute() {
        return $this->ratings->average('rating');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'joke_id');
    }
}
