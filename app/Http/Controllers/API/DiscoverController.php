<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use App\User;
use App\Movie;
use App\Http\Controllers\Controller;
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
		 $resp = Movie::whereHas("release_dates", function($query) {
			 $query->where("type", "in", "(4,5,6)");//->whereBetween("release_date", array(Carbon::now()->subWeek(), Carbon::now()->addWeeks(3)))->orderBy("release_date", "ASC");
		 })->orderBy("popularity", "DESC")->take(3)->get();
		 return $this->json_view($resp->toArray());
	 }
}

?>