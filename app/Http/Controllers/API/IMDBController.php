<?php

namespace App\Http\Controllers\API;

use App\Movie;
use Carbon\Carbon;
use App\Http\Requests\IMDBListRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Helper;

class IMDBController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     * @return Response
     */
	 public function top250(Request $request) {
		 $movies = Helper::get_from_imdb_py("top250");
		 return $this->json_view($movies);
	 }

   public function popular(Request $request) {
     $limit = 10;
     if ($request->query("limit") != NULL)
     {
        $limit = $request->query("limit");
     }
     $movies = Helper::get_from_imdb_py("popular", $limit);
     return $this->json_view($movies);
   }

   public function user_list(IMDBListRequest $request) {
     $movies = Helper::get_from_imdb_py("list", $request->query("listId"));
		 return $this->json_view($movies);
   }
}

?>
