<?php

declare (strict_types=1);
namespace ScobyAnalyticsDeps\RebelCode\WordPress\Http\Exception;

use ScobyAnalyticsDeps\Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;
/**
 * The base exception class for all other exceptions classes.
 */
class HttpException extends RuntimeException implements ClientExceptionInterface
{
}
