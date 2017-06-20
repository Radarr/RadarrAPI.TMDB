<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\User;
use App\Movie;
use App\StevenLuMovie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DBMaintenanceController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate()
    {
        $tables = DB::select('SHOW TABLES');
        $real_tables = array();
        foreach ($tables as $dict) {
            foreach ($dict as $key => $value) {
                $real_tables[] = $value;
            }
        }
        return response()->json($real_tables);
    }
}
