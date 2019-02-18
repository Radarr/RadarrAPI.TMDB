<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $hidden = ['movie_id'];

    public function getDataAttribute($value)
    {
        return json_decode(stripslashes($value));
    }
}
