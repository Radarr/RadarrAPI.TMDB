<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public function getAdditionalDataAttribute($value)
    {
        return json_decode(stripslashes($value));
    }
}
