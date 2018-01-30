<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "persons";

    protected $hidden = ["pivot"];

    public function movies() {
        return $this->belongsToMany("App\Movie", "credits")->withPivot('character', 'order', 'job', 'department', 'credit_id');
    }
}
