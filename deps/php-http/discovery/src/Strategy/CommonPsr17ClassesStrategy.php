<?php

namespace ScobyAnalyticsDeps\Http\Discovery\Strategy;

use ScobyAnalyticsDeps\Psr\Http\Message\RequestFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\ResponseFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\ServerRequestFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\StreamFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\UploadedFileFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\UriFactoryInterface;
/**
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * Don't miss updating src/Composer/Plugin.php when adding a new supported class.
 */
final class CommonPsr17ClassesStrategy implements DiscoveryStrategy
{
    /**
     * @var array
     */
    private static $classes = [RequestFactoryInterface::class => ['ScobyAnalyticsDeps\\Phalcon\\Http\\Message\\RequestFactory', 'ScobyAnalyticsDeps\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'ScobyAnalyticsDeps\\GuzzleHttp\\Psr7\\HttpFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Diactoros\\RequestFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Guzzle\\RequestFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Slim\\RequestFactory', 'ScobyAnalyticsDeps\\Laminas\\Diactoros\\RequestFactory', 'ScobyAnalyticsDeps\\Slim\\Psr7\\Factory\\RequestFactory', 'ScobyAnalyticsDeps\\HttpSoft\\Message\\RequestFactory'], ResponseFactoryInterface::class => ['ScobyAnalyticsDeps\\Phalcon\\Http\\Message\\ResponseFactory', 'ScobyAnalyticsDeps\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'ScobyAnalyticsDeps\\GuzzleHttp\\Psr7\\HttpFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Diactoros\\ResponseFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Guzzle\\ResponseFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Slim\\ResponseFactory', 'ScobyAnalyticsDeps\\Laminas\\Diactoros\\ResponseFactory', 'ScobyAnalyticsDeps\\Slim\\Psr7\\Factory\\ResponseFactory', 'ScobyAnalyticsDeps\\HttpSoft\\Message\\ResponseFactory'], ServerRequestFactoryInterface::class => ['ScobyAnalyticsDeps\\Phalcon\\Http\\Message\\ServerRequestFactory', 'ScobyAnalyticsDeps\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'ScobyAnalyticsDeps\\GuzzleHttp\\Psr7\\HttpFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Diactoros\\ServerRequestFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Guzzle\\ServerRequestFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Slim\\ServerRequestFactory', 'ScobyAnalyticsDeps\\Laminas\\Diactoros\\ServerRequestFactory', 'ScobyAnalyticsDeps\\Slim\\Psr7\\Factory\\ServerRequestFactory', 'ScobyAnalyticsDeps\\HttpSoft\\Message\\ServerRequestFactory'], StreamFactoryInterface::class => ['ScobyAnalyticsDeps\\Phalcon\\Http\\Message\\StreamFactory', 'ScobyAnalyticsDeps\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'ScobyAnalyticsDeps\\GuzzleHttp\\Psr7\\HttpFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Diactoros\\StreamFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Guzzle\\StreamFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Slim\\StreamFactory', 'ScobyAnalyticsDeps\\Laminas\\Diactoros\\StreamFactory', 'ScobyAnalyticsDeps\\Slim\\Psr7\\Factory\\StreamFactory', 'ScobyAnalyticsDeps\\HttpSoft\\Message\\StreamFactory'], UploadedFileFactoryInterface::class => ['ScobyAnalyticsDeps\\Phalcon\\Http\\Message\\UploadedFileFactory', 'ScobyAnalyticsDeps\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'ScobyAnalyticsDeps\\GuzzleHttp\\Psr7\\HttpFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Diactoros\\UploadedFileFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Guzzle\\UploadedFileFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Slim\\UploadedFileFactory', 'ScobyAnalyticsDeps\\Laminas\\Diactoros\\UploadedFileFactory', 'ScobyAnalyticsDeps\\Slim\\Psr7\\Factory\\UploadedFileFactory', 'ScobyAnalyticsDeps\\HttpSoft\\Message\\UploadedFileFactory'], UriFactoryInterface::class => ['ScobyAnalyticsDeps\\Phalcon\\Http\\Message\\UriFactory', 'ScobyAnalyticsDeps\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'ScobyAnalyticsDeps\\GuzzleHttp\\Psr7\\HttpFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Diactoros\\UriFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Guzzle\\UriFactory', 'ScobyAnalyticsDeps\\Http\\Factory\\Slim\\UriFactory', 'ScobyAnalyticsDeps\\Laminas\\Diactoros\\UriFactory', 'ScobyAnalyticsDeps\\Slim\\Psr7\\Factory\\UriFactory', 'ScobyAnalyticsDeps\\HttpSoft\\Message\\UriFactory']];
    public static function getCandidates($type)
    {
        $candidates = [];
        if (isset(self::$classes[$type])) {
            foreach (self::$classes[$type] as $class) {
                $candidates[] = ['class' => $class, 'condition' => [$class]];
            }
        }
        return $candidates;
    }
}
