<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Helper;

Relation::morphMap([
    'title' => 'App\TitleMapping',
    'year' => 'App\YearMapping',
]);

class Mapping extends Model
{
    protected $connection = 'mappings_mysql';

    protected $fillable = array('tmdbid', "imdbid");
    //

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function mapable()
    {
        return $this->morphTo();
    }

    public function jsonSerialize()
    {
        $arr = $this->toArray();
        //var_dump($this->mappable());
        return array_merge( $this->mapable->toArray(), $arr);
    }

    public static function newMapping($values, $class)
    {
        $sub_class = new $class($values);
        $sub_class->save();

        $mapping = new Mapping($values);
        $mapping->mapable()->associate($sub_class);
        $mapping->save();

        return $mapping;
    }

    public function vote($direction = 1)
    {
        $this->report_count = $this->report_count + $direction;
        $this->total_reports += 1;
        $this->save();
    }

}

class TitleMapping extends Model {
    protected $table = "title_mappings";

    protected $connection = 'mappings_mysql';

    protected $fillable = array('aka_title', "aka_clean_title");

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function __construct(array $attributes = array(), $value =null)
    {
        if (array_key_exists("aka_title", $attributes) && !array_key_exists("aka_clean_title", $attributes))
        {
            $aka_title = $attributes["aka_title"];
            $attributes["aka_clean_title"] = Helper::clean_title($aka_title);
        }
        /* override your model constructor */
        parent::__construct($attributes);

    }

    public function map()
    {
        $morph = $this->morphMany('App\Mapping', 'mapable', "mapable_type", "mapable_id");
        return $morph;
    }
}

class YearMapping extends Model {
    protected $table = "year_mappings";

    protected $connection = 'mappings_mysql';

    protected $fillable = array('aka_year');

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function map()
    {
        return $this->morphMany('App\Mapping', 'mapable', "mapable_type", "mapable_id");
    }
}
