<?php

namespace App\Http\Middleware;

class CheckDBMaintAPI extends CheckDBMaint
{
    public function add_warning($response)
    {
        $orig = $response->original;
        $orig['warnings'] = ['title' => 'Database Maintenance', 'details' => 'Currently performing Database Maintenance. Data may be stale. No Data may be written to API.'];

        return $response->setData($orig);
    }
}
