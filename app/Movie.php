<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
        "adult" => "boolean",
    ];

    protected $hidden = ["cast", "crew"];

    protected $appends = ["credits"];

    protected $with = ["collection", "keywords", "cast", "crew", "trailer", "ratings", "release_dates", "alternative_titles", "translations"];

    public function getCreditsAttribute()
    {
        return ["cast" => $this->cast, "crew" => $this->crew];
    }

    public function scopeDefaultWith()
    {
        return $this->with(["similar", "recommendations"]);
    }

    public function getOverviewAttribute($value)
    {
        $language = Input::get("language");
        if ($language == null || $language == "") return $value;
        $iso_639 = str_before($language, "_");
        $iso_3166 = str_after($language, "_");
        $translation = $this->getTranslationMatching($iso_639, $iso_3166);
        if ($translation != null && $translation->data != null) return $translation->data->overview;
        $translation = $this->getTranslationMatching($iso_639, null);
        if ($translation != null && $translation->data != null) return $translation->data->overview;
        return $value;
    }

    public function getTitleAttribute($value)
    {
        $language = Input::get("language");
        if ($language == null || $language == "") return $value;
        $iso_639 = str_before($language, "_");
        $iso_3166 = str_after($language, "_");
        $translation = $this->getTranslationMatching($iso_639, $iso_3166);
        if ($translation != null && $translation->data != null) return $translation->data->title;
        $translation = $this->getTranslationMatching($iso_639, null);
        if ($translation != null && $translation->data != null) return $translation->data->title;
        return $value;
    }

    private function getTranslationMatching($iso_639 = null, $iso_3166 = null)
    {
        foreach ($this->translations as $translation) {
            if ($translation->iso_639_1 == $iso_639 && ($translation->iso_3166_1 == $iso_3166 || $iso_3166 == null)) {
                return $translation;
            }
        }
    }

    public function scopeFilter($query) {
        $year_range = Input::get("year");
        if ($year_range != null) {
            $start_year = 1880;
            $end_year = null;
            if (stripos($year_range, "<>") === false) {
                $start_year = $year_range;
                $end_year = $year_range;
            } else {
                $start_year = explode("<>", $year_range)[0];
                $end_year = explode("<>", $year_range)[1];
            }

            $query = $query->where("release_date", ">", Carbon::create($start_year-1, 12, 31));
            if ($end_year != null) {
                $query = $query->where("release_date", "<", Carbon::create($end_year+1, 1, 1));
            }
        }

        $genres = Input::get("genre");

        if ($genres != null) {
            $ids = explode(",", $genres);
            $query->whereHas("genres", function($q) use ($ids){
                $q->whereIn("id", $ids);
            });
        }

        $query = $this->filter($query, "budget", "budget");
        $query = $this->filter($query, "revenue", "revenue");
        $query = $this->filter($query, "runtime", "runtime");
        $query = $this->filter($query, "popularity", "popularity");
        $adult = Input::get("adult", false);
        if ($adult == false) {
            $query = $query->where("adult", "=", 0);
        }

        $certified = (bool)Input::get("certified_fresh", false);
        if ($certified == true) {
            $query = $query->whereIn("id", function($query) {
               $query->select("movie_id")->from("ratings")->where("certified_fresh", true);
            });
        }

        return $query;
    }

    public function filter($query, $column, $input, $value_transformer = null) {
        $value = Input::get($input);

        if ($value_transformer == null) {
            $value_transformer = function($value, $sign) {
                return $value;
            };
        }

        if ($value != null) {
            if (stripos($value, "<>") === false) {
                //We don't have a range!
                $query = $query->where($column, "=", $value_transformer($value, "="));
            } else {
                $start = explode("<>", $value)[0];
                $end = explode("<>", $value)[1];
                $query = $query->where($column, ">", $value_transformer($start, ">"));
                $query = $query->where($column, "<", $value_transformer($end, "<"));
            }
        }

        return $query;
    }

    public function trailer() {
        return $this->hasOne("App\Video")->whereIn("type", ["trailer", "teaser"])->orderByDesc("size");
    }

    public function ratings() {
        return $this->hasMany("App\Rating");
    }

    public function genres() {
        return $this->belongsToMany("App\Genre");
    }

    public function keywords() {
        return $this->belongsToMany("App\Keyword", "movie_to_keyword");
    }

    public function release_dates() {
        return $this->hasMany("App\ReleaseDate");
    }

    public function alternative_titles() {
        return $this->hasMany("App\AlternativeTitle");
    }

    public function cast() {
        return $this->belongsToMany("App\Person", "credits")->wherePivot("type", "=", "cast")->withPivot('character', 'order', 'credit_id');
    }

    public function crew() {
        return $this->belongsToMany("App\Person", "credits")->wherePivot("type", "=", "crew")->withPivot('job', 'department', 'credit_id');;
    }

    public function similar() {
        return $this->belongsToMany('App\Movie', 'similar', 'movie_id', 'similar_id');
    }

    public function recommendations() {
        return $this->belongsToMany('App\Movie', 'recommendations', 'movie_id', 'recommended_id');
    }

    public function recommendedFrom() {
        return $this->belongsToMany('App\Movie', 'recommendations', 'recommended_id', 'movie_id');
    }

    public function collection() {
        return $this->belongsTo("App\Collection");
    }

    public function credits() {
        return $this->hasMany("App\Credit");
    }

    public function translations() {
        return $this->hasMany("App\Translation");
    }

    public function mappings()
    {
        return $this->hasMany("App\Mapping", "tmdbid", "id");
    }

    public function createMappingMovie() {
        return new MappingMovie(["id" => $this->id, "title" => $this->title, "imdb_id" => $this->imdb_id]);
    }
}

