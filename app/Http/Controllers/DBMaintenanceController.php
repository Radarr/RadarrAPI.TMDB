<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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
        $real_tables = [];
        foreach ($tables as $dict) {
            foreach ($dict as $key => $value) {
                $real_tables[] = $value;
            }
        }

        return response()->json($real_tables);
    }
}
