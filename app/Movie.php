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

    protected $casts = [
        'adult' => 'boolean',
    ];

    public function release_dates()
    {
        return $this->hasMany("App\ReleaseDate", 'tmdbid', 'id');
    }

    public function physical_release()
    {
        return $this->hasOne("App\ReleaseDate", 'tmdbid', 'id')->whereIn('type', [4, 5, 6])->orderBy('release_date', 'ASC')->limit(1);
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $physical_release = $this->physical_release;
        if ($physical_release != null) {
            $arr['physical_release'] = $physical_release->release_date;
            $arr['physical_release_note'] = $physical_release->note;
        }
        $arr['genres'] = explode(',', $this->genres);
        //unset($arr["genres"]);
        return $arr;
    }

    public function createMappingMovie()
    {
        return new MappingMovie(['id' => $this->id, 'title' => $this->title, 'imdb_id' => $this->imdb_id]);
    }
}
