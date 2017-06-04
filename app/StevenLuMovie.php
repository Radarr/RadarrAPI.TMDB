<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StevenLuMovie extends Model
{
    //
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = "stevenlu";

    public function TMDBMovie()
    {
        $this->hasOne("Movie", "imdb_id", "imdb_id");
    }
}
