<?php
/**
 * Created by PhpStorm.
 * User: leonardogalli
 * Date: 23.06.17
 * Time: 13:28.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingMovie extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'title', 'imdb_id'];

    protected $table = 'movies';

    protected $connection = 'mappings_mysql';

    public function mappings()
    {
        return $this->hasMany("App\Mapping", 'tmdbid', 'id');
    }
}
