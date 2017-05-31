<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReleaseDate extends Model
{
    //
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $table = 'release_dates';
}
