<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "persons";

    protected $hidden = ["pivot"];

    protected $appends = ["department", "job", "order", "character"];

    public function movies() {
        return $this->belongsToMany("App\Movie", "credits")->withPivot('type', 'character', 'order', 'job', 'department', 'credit_id');
    }

    public function getDepartmentAttribute()
    {
        if ($this->pivot == null)
        {
            return null;
        }
        return $this->pivot->department;
    }

    public function getJobAttribute()
    {
        if ($this->pivot == null)
        {
            return null;
        }
        return $this->pivot->job;
    }

    public function getOrderAttribute()
    {
        if ($this->pivot == null)
        {
            return null;
        }
        return $this->pivot->order;
    }

    public function getCharacterAttribute()
    {
        if ($this->pivot == null)
        {
            return null;
        }
        return $this->pivot->character;
    }
}
