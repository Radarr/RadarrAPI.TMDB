<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    //protected $with = [];

    public function movies()
    {
        return $this->hasMany("App\Movie");
    }
}
