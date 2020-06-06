<?php

namespace App\Http\Controllers\API;

use App\Movie;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function suggestions(Request $request)
    {
        $query = $request->query('query');
        $title = Helper::clean_title($query);
        //SELECT m.id, m.title as name, m.release_year, m.poster_path FROM movies m WHERE m.clean_title LIKE '%$term%' ORDER BY m.popularity DESC LIMIT 5
        $movies = Cache::remember("suggestions.$title", new Carbon('tomorrow midnight'), function () use ($title) {
            return Movie::select('id', 'title as name', 'release_year', 'poster_path')->where('clean_title', 'LIKE', '%'.$title.'%')->orderBy('popularity', 'desc')->take(5)->get();
        }); //DB::statement("SELECT m.id, m.title as name, m.release_year, m.poster_path FROM movies m WHERE m.clean_title LIKE '%$title%' ORDER BY m.popularity DESC LIMIT 5");

        return response()->json($movies->toArray());
    }
}
