<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class JSONController extends Controller
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     * @return Response
     */
	public function json_view($object) {
		return response(json_encode($this->utf8ize($object)), 200)->header("Content-Type", "application/json")->header("Access-Control-Allow-Origin", "*")->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	}
	
	public function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
        		$mixed[$key] = $this->utf8ize($value);
    		}
		} else if (is_string ($mixed)) {
			return utf8_encode($mixed);
		}
		
		return $mixed;
	}
	
	public function ping() {
		return $this->json_view(array("message" => "pong"));
	}
}

?>