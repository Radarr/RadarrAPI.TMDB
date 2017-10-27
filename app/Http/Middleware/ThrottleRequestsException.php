<?php
/**
 * Created by PhpStorm.
 * User: leonardogalli
 * Date: 13.08.17
 * Time: 17:34.
 */

namespace App\Http\Middleware;

use App\Exceptions\TooManyRequestsHttpException;
use Illuminate\Routing\Middleware\ThrottleRequests;

class ThrottleRequestsException extends ThrottleRequests
{
    /**
     * Create a 'too many attempts' response.
     *
     * @param string $key
     * @param int    $maxAttempts
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        throw new TooManyRequestsHttpException($retryAfter, $maxAttempts);
    }
}
