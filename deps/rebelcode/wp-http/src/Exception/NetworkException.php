<?php

declare (strict_types=1);
namespace ScobyAnalyticsDeps\RebelCode\WordPress\Http\Exception;

use ScobyAnalyticsDeps\Psr\Http\Client\NetworkExceptionInterface;
/**
 * An exception that is thrown when a network connection cannot be established.
 */
class NetworkException extends RequestException implements NetworkExceptionInterface
{
}
