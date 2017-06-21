<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Helpers\IMDBAPI;
use Illuminate\Support\Facades\DB;
use App\Movie;

class TestJSONController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     * @return Response
     */
	 public function hello($name = "Leo") {
		 $resp = array();
		 $resp["message"] = "Hello $name";


		 return response()->json($resp);
	 }
}




?>


