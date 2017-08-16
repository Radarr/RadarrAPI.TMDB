<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use App\User;
use App\Movie;
use App\StevenLuMovie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class DiscoverController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     * @return Response
     */
	 public function upcoming() {
		 $resp = array();
		 $resp = Cache::remember("discovery.upcoming", Carbon::now()->addHours(12), function (){
        return Movie::whereHas("release_dates", function($query) {
   			 $query->whereIn("type", array(4,5,6))->whereBetween("release_date", array(Carbon::now()->subWeek(), Carbon::now()->addWeeks(3)))->orderBy("release_date", "ASC");
   		 })->where("adult", "=", "0")->orderBy("popularity", "DESC")->get()->toArray();
     });
         $resp = $this->filterMovies($resp);
		 return response()->json($resp);
	 }

   public function popular() {
      $movies = Cache::remember("discovery.popular", new Carbon('tomorrow midnight'), function(){
          return array_values(StevenLuMovie::all()->sortByDesc("TMDBMovie.popularity")->toArray());
      });
       $movies = $this->filterMovies($movies);
      return response()->json($movies);
   }


  protected $vote_max = 0;
  protected $pop_max = 0;
  protected $count_max = 0;

   public function recommendations(Request $request) {
      $ids = $request->input("tmdbIds");
      $ignoredIds = $request->input("ignoredIds");
       $movies = Cache::remember("discovery.recommendations.$ids.$ignoredIds", new Carbon("tomorrow midnight"), function() use($ignoredIds, $ids){
           if ($ignoredIds != "")
           {
               $ignoredIds = ",".$ignoredIds;
           }

           if ($ids == "" || $ids == null)
           {
               abort(422, "Please add some movies before using our recommendation engine :)");
           }
           $movies_db = DB::select("SELECT mo.id, mo.popularity, mo.imdb_id, mo.title, mo.overview, mo.vote_average, mo.vote_count, mo.tagline, mo.poster_path, mo.release_date, mo.release_year, mo.trailer_key, mo.trailer_site, mo.backdrop_path, mo.homepage, mo.runtime, mo.countO, mo.genres, mo.runtime, mo.adult FROM ( SELECT m.*, r.recommended_id, r.tmdbid, r.id as rid, count(m.id) as countO FROM movies m, recommendations r WHERE m.id = r.recommended_id AND r.tmdbid in ($ids) AND r.recommended_id not in ($ids$ignoredIds) AND m.adult = 0 GROUP BY m.id ) as mo;");
           $movies = json_decode(json_encode($movies_db), true);

           $this->count_max = maximum($movies, "countO");
           $this->pop_max = maximum($movies, "popularity");
           $this->vote_max = maximum($movies, "vote_average");

           usort($movies, array($this, "compare_score"));

           $movies = array_slice($movies, 0, 30);
           $resp = [];

           foreach ($movies as $movie) {
               unset($movie["countO"]);
               $movie["genres"] = explode(",", $movie["genres"]);
               $movie["adult"] = $movie["adult"] == 1;
               $resp[] = $movie;
           }

           return $resp;
       });

       $movies = $this->filterMovies($movies);

      return response()->json($movies);
   }

   function filterMovies($movies)
   {
       $yearLower = Input::get('yearLower', 1800);
       $yearUpper = Input::get('yearUpper', 2300);
       $genreIds = Input::get('genreIds', "");
       if (!(is_array($genreIds) || $genreIds === null))
       {
            $genreIds = explode(",", $genreIds);
       }
       return array_filter($movies, function($value) use ($yearLower, $yearUpper, $genreIds) {
           //dd($genreIds);
            return $value["release_year"] >= $yearLower && $value["release_year"] <= $yearUpper && ($genreIds != array("") ? count(array_intersect($value["genres"], $genreIds)) > 0 : true);
       });
   }

   function score ($elem)
   {

   		return (float)($elem["vote_average"]) / ($this->vote_max) + (float)($elem["popularity"]) / (2*$this->pop_max) + (float)($elem["countO"]) / (2*$this->count_max);
   }

   function compare_score ($a, $b) {

     if ($this->score($a) > $this->score($b)) {
       return -1;
     } else {
       return 1;
     }
   }
}

function maximum($arr, $key) {
  $max = 0.0;

  foreach ($arr as $elem) {
    $val = $elem[$key];
    if ((double)$val > $max) {
      $max = $val;
    }
  }

  return $max;
}

?>
