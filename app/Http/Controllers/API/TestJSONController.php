<?php

namespace App\Http\Controllers\API;

use Symfony\Component\HttpFoundation\Response;

class TestJSONController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function hello($name = 'Leo')
    {
        $resp = [];
        $resp['message'] = "Hello $name";

        return response()->json($resp);
    }
}

?>


