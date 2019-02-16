<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $with = ["cast"];

    public function cast() {
        return $this->hasMany("App\Person")->where("type", "=", "cast");
    }
}
