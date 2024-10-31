<?php

declare (strict_types=1);
namespace ScobyAnalyticsDeps\RebelCode\WordPress\Http\Exception;

use ScobyAnalyticsDeps\RebelCode\WordPress\Http\Middleware\HttpErrorsToExceptions;
/**
 * An exception that is thrown by the {@link HttpErrorsToExceptions} middleware for 4xx responses.
 */
class ClientErrorException extends BadResponseException
{
}
