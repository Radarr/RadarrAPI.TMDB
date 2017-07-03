<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mappings_mysql';

    protected $fillable = ["type", "mappings_id", "ip"];

    public $timestamps = false;

    public function mapping()
    {
        return $this->hasOne("App\Mapping", "id", "mappings_id");
    }

    public function toArray()
    {
        $arr = parent::toArray();

        $arr["mapping"] = $this->mapping->toArray();
        unset($arr["ip"]);
        $arr["mapping"]["movie"] = MappingMovie::find($arr["mapping"]["tmdbid"]);
        return $arr;
    }
}

abstract class EventType extends Enum {
    const AddedMapping = 0;
    const ApproveMapping = 1;
    const DisapproveMapping = 2;
    const LockedMapping = 3;
}

abstract class Enum {
    static function getKeys(){
        $class = new ReflectionClass(get_called_class());
        return array_keys($class->getConstants());
    }
}
