<?php

declare (strict_types=1);
namespace ScobyAnalyticsDeps\RebelCode\WordPress\Http;

use ScobyAnalyticsDeps\Psr\Http\Message\RequestInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\ResponseInterface;
/**
 * Interface for an object that can handle a request and provide a response for it.
 */
interface HandlerInterface
{
    /**
     * Handles a request and creates a response.
     *
     * @param RequestInterface $request The request to handle.
     *
     * @return ResponseInterface The response for the handled request.
     */
    public function handle(RequestInterface $request) : ResponseInterface;
}
