<?php

namespace App\Http\Controllers\API;

use App\Collection;
use App\Movie;
use App\Person;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Helper;
use Tmdb\ApiToken;
use Tmdb\Client;

class SearchController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     * @return Response
     */
	 public function suggestions(Request $request) {
		 $query = $request->query("query");
		 $title = Helper::clean_title($query);
		 //SELECT m.id, m.title as name, m.release_year, m.poster_path FROM movies m WHERE m.clean_title LIKE '%$term%' ORDER BY m.popularity DESC LIMIT 5
		 $movies = Cache::remember("suggestions.$title", new Carbon('tomorrow midnight'), function() use ($title) {
			 return Movie::select("id", "title as name", "release_year", "poster_path")->where("clean_title", "LIKE", "%".$title."%")->orderBy("popularity", "desc")->take(5)->get();
		 });//DB::statement("SELECT m.id, m.title as name, m.release_year, m.poster_path FROM movies m WHERE m.clean_title LIKE '%$title%' ORDER BY m.popularity DESC LIMIT 5");
		 return response()->json($movies->toArray());
	 }

	 public function multi(string $term)
	 {
	 	// Heavy caching, since the search operation is kinda expensive!
	 	return Cache::remember("search.multi.$term", new Carbon("tomorrow midnight"), function() use ($term) {
	 		$movie_limit = 15;
	 		$actor_limit = 3;
	 		$collection_limit = 3;

			$client = new Client(new ApiToken(env("TMDB_API_KEY")));

			$movie_results = $client->getSearchApi()->searchMovies($term);
			$movie_ids = array();
			foreach ($movie_results["results"] as $m_res) {
				$movie_ids[] = $m_res["id"];
			}
			$movies = Movie::whereIn("id", $movie_ids)->limit($movie_limit)->get();

            $person_results = $client->getSearchApi()->searchPersons($term);
            $person_ids = array();
            foreach ($person_results["results"] as $m_res) {
                $person_ids[] = $m_res["id"];
            }
            $persons = Person::whereIn("id", $person_ids)->with(["movies"])->limit($actor_limit)->get();

            $collection_results = $client->getSearchApi()->searchCollection($term);
            $collection_ids = array();
            foreach ($collection_results["results"] as $m_res) {
                $collection_ids[] = $m_res["id"];
            }
            $collections = Collection::whereIn("id", $collection_ids)->with(["movies"])->limit($collection_limit)->get();

			return [
				"movies" => $movies,
				"collections" => $collections,
				"persons" => $persons
			];
		});
	 }
}

?>
