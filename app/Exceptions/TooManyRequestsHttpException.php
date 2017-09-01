<?php
/**
 * Created by PhpStorm.
 * User: leonardogalli
 * Date: 13.08.17
 * Time: 17:42
 */

namespace App\Exceptions;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TooManyRequestsHttpException extends HttpException
{
    /**
     * Constructor.
     *
     * @param int|string $retryAfter The number of seconds or HTTP-date after which the request may be retried
     * @param string     $message    The internal exception message
     * @param \Exception $previous   The previous exception
     * @param int        $code       The internal exception code
     */
    public function __construct($retryAfter = null, $maxAttempts = null, \Exception $previous = null, $code = 0)
    {
        $headers = array();
        $message = "You have sent too many requests.";

        if ($retryAfter) {
            $headers = array('Retry-After' => $retryAfter);
            $headers['X-RateLimit-Reset'] = Carbon::now()->getTimestamp() + $retryAfter;
            $message .= " Please retry after $retryAfter seconds.";
        }

        if ($maxAttempts) {
            $headers['X-RateLimit-Limit'] = $maxAttempts;
            $headers['X-RateLimit-Remaining'] = 0;
        }

        parent::__construct(429, $message, $previous, $headers, $code);
    }
}
