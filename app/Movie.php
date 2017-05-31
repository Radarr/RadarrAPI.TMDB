<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    //
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function release_dates() {
	    return $this->hasMany("App\ReleaseDate", "tmdbid", "id");
    }
}
