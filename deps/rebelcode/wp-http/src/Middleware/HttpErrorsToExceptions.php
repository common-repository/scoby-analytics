<?php

declare (strict_types=1);
namespace ScobyAnalyticsDeps\RebelCode\WordPress\Http\Middleware;

use ScobyAnalyticsDeps\Psr\Http\Message\RequestInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\ResponseInterface;
use ScobyAnalyticsDeps\RebelCode\WordPress\Http\Exception\BadResponseException;
use ScobyAnalyticsDeps\RebelCode\WordPress\Http\Middleware;
/**
 * A middleware handler that throws exceptions for responses with 4xx or 5xx status codes.
 */
class HttpErrorsToExceptions extends Middleware
{
    /**
     * @inheritDoc
     */
    public function handle(RequestInterface $request) : ResponseInterface
    {
        $response = $this->next($request);
        $code = $response->getStatusCode();
        if ($code < 400) {
            return $response;
        }
        throw BadResponseException::create($request, $response);
    }
}
