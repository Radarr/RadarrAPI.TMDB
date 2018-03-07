<?php

namespace App\Http\Middleware;

use ChaseConey\LaravelDatadogHelper\Datadog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DatadogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $response = $next($request);
        if (config('datadog-helper.enabled', false)) {
            static::logDuration($request, $response, $startTime);
        }

        return $response;
    }

    /**
     * Logs the duration of a specific request through the application.
     *
     * @param Request  $request
     * @param Response $response
     * @param float    $startTime
     */
    protected static function logDuration($request, $response, $startTime)
    {
        $duration = microtime(true) - $startTime;

        $ua = $request->header('User-Agent');
        $re = '/Radarr\/(?P<version>(\d+\.)+\d+)\s\((?P<os_name>(Osx|Linux|Windows))\s(?P<os_version>(\d+\.)+\d+)\)/i';
        preg_match($re, $ua, $matches);

        $tags = [
            'url'         => $request->getSchemeAndHttpHost().$request->getRequestUri(),
            'status_code' => $response->getStatusCode(),
            'radarr',
            'version'    => $matches['version'] ?? 'unknown',
            'os_name'    => $matches['os_name'] ?? 'unknown',
            'os_version' => $matches['os_version'] ?? 'unknown',
        ];

        Datadog::timing('request_time', $duration, 1, $tags);

        Datadog::set('unique_users', $request->ip(), 1.0, $tags);
    }
}
