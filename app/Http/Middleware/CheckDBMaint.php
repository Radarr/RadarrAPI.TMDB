<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class CheckDBMaint
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $request->attributes->add(["is_db_maintenance" => $this->is_db_maintenance()]);
      $response = $next($request);

      if ($this->is_db_maintenance())
      {
          $response = $this->add_warning($response);
      }

      //dd("d");

      return $response;
    }

    public function is_db_maintenance()
    {
        return Storage::disk()->exists("db.down");
    }

    public function add_warning($response)
    {
        return $response;
    }
}
