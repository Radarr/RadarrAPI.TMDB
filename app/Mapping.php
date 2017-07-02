<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;
use Helper;
use MappingsCache;
use Carbon\Carbon;


class Mapping extends Model
{
    protected $connection = 'mappings_mysql';

    protected $fillable = array('tmdbid', "info_type", "info_id");

    protected $casts = [
        "locked" => "boolean",
    ];


    //

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function info()
    {
        if ($this->info_type == "title")
        {
            return $this->hasOne("App\TitleInfo", "id", "info_id");
        }

        return $this->hasOne("App\YearInfo", "id", "info_id");
    }

    public function title_info()
    {
        return $this->hasOne("App\TitleInfo", "id", "info_id");
    }

    public function year_info()
    {
        return $this->hasOne("App\YearInfo", "id", "info_id");
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr["info"] = $this->info->toArray();
        $total = $this->vote_count;

        //$random_variation = round($total / 10.0);

        $variation = 0;//random_int(-$random_variation, $random_variation);
        $arr["votes"] += $variation;
        $arr["vote_count"] += abs($variation);
        return $arr;
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
        if ($this->locked)
        {
            return;
        }
        
        $ip = md5($_SERVER['REMOTE_ADDR']);
        if (Event::whereIn("type", [EventType::AddedMapping, EventType::ApproveMapping, EventType::DisapproveMapping])->where("mappings_id", "=", $this->id)->where("ip", "=", $ip)->whereBetween("date", array(Carbon::now()->addDays(-1), Carbon::now()))->first())
        {
            return;
        }
        $this->votes = $this->votes + $direction;
        $this->vote_count += 1;
        $event_type = EventType::ApproveMapping;
        if ($direction == -1)
        {
            $event_type = EventType::DisapproveMapping;
        }
        $event = new Event(["type" => $event_type, "mappings_id" => $this->id, "ip" => $ip]);
        $event->save();
        $this->save();
    }

}

class TitleInfo extends Model {
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

}

class YearInfo extends Model {
    protected $table = "year_mappings";

    protected $connection = 'mappings_mysql';

    protected $fillable = array('aka_year');

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
