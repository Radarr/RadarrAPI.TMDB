<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use App\User;
use App\Movie;
use App\Mapping;
use App\TitleMapping;
use App\YearMapping;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Helper;
use MappingsCache;

class MappingsController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     * @return Response
     */
	 public function get(Request $request) {
     $id = $request->query("id");
     $tmdbid = $request->query("tmdbid");
     $imdbid = $request->query("imdbid");

     $type = $request->query("type");

     $query = array("id" => $id);

     if (isset($tmdbid)) {
       $query = array("tmdbid" => $tmdbid);
     } else if (isset($imdbid)) {
       $query = array("imdbid" => $imdbid);
     }

     if (isset($type))
     {
        $query["mapable_type"] = $type;
     }

     $mappings = MappingsCache::rememberQuery($query);/*Cache::remember($key, Carbon::now()->addMinutes(15), function() use ($query) {
       return Mapping::where($query)->get()->toArray();
     });*/


		 return $this->json_view($mappings);
	 }

   public function add(Request $request) {
      $class = "App\TitleMapping";
      $values = array(
          "tmdbid" => $request->query("tmdbid"),
          "imdbid" => $request->query("imdbid")
      );
      $new_mapping = null;
      if ($request->query("type") == "title")
      {
          $aka_title = $request->query("aka_title");
          $clean_title = Helper::clean_title($aka_title);

          $mapping = TitleMapping::where("aka_clean_title", $clean_title)->first();
          if (isset($mapping))
          {
              $mapping->map->first()->vote();
              $new_mapping = $mapping->map->first();
          } else
          {
              $values["aka_title"] = $aka_title;
              $new_mapping = Mapping::newMapping($values, $class);
          }

      } else if ($request->query("type") == "year")
      {
          $class = "App\YearMapping";

          $aka_year = $request->query("aka_year");

          $mapping = YearMapping::where("aka_year", $aka_year)->first();
          if (isset($mapping))
          {
              $mapping->map->first()->vote();
              $new_mapping = $mapping->map->first();
          } else
          {
              $values["aka_year"] = $aka_year;
              $new_mapping = Mapping::newMapping($values, $class);
          }
      }

      return $this->json_view($new_mapping);
   }

   public function vote(Request $request) {
      $id = $request->query("id");
      $direction = $request->query("direction");
      if (!isset($direction))
      {
          $direction = 1;
      }
      $mapping = Mapping::find($id);
      $mapping->vote($direction);

      return $this->json_view($mapping);
   }
}

?>
