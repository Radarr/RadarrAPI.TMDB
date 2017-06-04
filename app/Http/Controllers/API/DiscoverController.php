<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use App\User;
use App\Movie;
use App\StevenLuMovie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

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
   		 })->orderBy("popularity", "DESC")->get()->toArray();
     });
		 return $this->json_view($resp);
	 }

   public function popular() {
      $movies = Cache::remember("discovery.popular", new Carbon('tomorrow midnight'), function(){
          return array_values(StevenLuMovie::all()->sortByDesc("TMDBMovie.popularity")->toArray());
      });
      return $this->json_view($movies);
   }

   public function recommendations(Request $request) {

   }
}

?>
