<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ScobyAnalyticsDeps\Symfony\Component\HttpClient;

use ScobyAnalyticsDeps\Http\Discovery\Psr17Factory;
use ScobyAnalyticsDeps\Http\Discovery\Psr17FactoryDiscovery;
use ScobyAnalyticsDeps\Nyholm\Psr7\Factory\Psr17Factory as NyholmPsr17Factory;
use ScobyAnalyticsDeps\Nyholm\Psr7\Request;
use ScobyAnalyticsDeps\Nyholm\Psr7\Uri;
use ScobyAnalyticsDeps\Psr\Http\Client\ClientInterface;
use ScobyAnalyticsDeps\Psr\Http\Client\NetworkExceptionInterface;
use ScobyAnalyticsDeps\Psr\Http\Client\RequestExceptionInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\RequestFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\RequestInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\ResponseFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\ResponseInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\StreamFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\StreamInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\UriFactoryInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\UriInterface;
use ScobyAnalyticsDeps\Symfony\Component\HttpClient\Internal\HttplugWaitLoop;
use ScobyAnalyticsDeps\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use ScobyAnalyticsDeps\Symfony\Contracts\HttpClient\HttpClientInterface;
use ScobyAnalyticsDeps\Symfony\Contracts\Service\ResetInterface;
if (!\interface_exists(ClientInterface::class)) {
    throw new \LogicException('You cannot use the "Symfony\\Component\\HttpClient\\Psr18Client" as the "psr/http-client" package is not installed. Try running "composer require php-http/discovery psr/http-client-implementation:*".');
}
if (!\interface_exists(RequestFactoryInterface::class)) {
    throw new \LogicException('You cannot use the "Symfony\\Component\\HttpClient\\Psr18Client" as the "psr/http-factory" package is not installed. Try running "composer require php-http/discovery psr/http-factory-implementation:*".');
}
/**
 * An adapter to turn a Symfony HttpClientInterface into a PSR-18 ClientInterface.
 *
 * Run "composer require php-http/discovery psr/http-client-implementation:*"
 * to get the required dependencies.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class Psr18Client implements ClientInterface, RequestFactoryInterface, StreamFactoryInterface, UriFactoryInterface, ResetInterface
{
    private HttpClientInterface $client;
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;
    public function __construct(?HttpClientInterface $client = null, ?ResponseFactoryInterface $responseFactory = null, ?StreamFactoryInterface $streamFactory = null)
    {
        $this->client = $client ?? HttpClient::create();
        $streamFactory ??= $responseFactory instanceof StreamFactoryInterface ? $responseFactory : null;
        if (null === $responseFactory || null === $streamFactory) {
            if (\class_exists(Psr17Factory::class)) {
                $psr17Factory = new Psr17Factory();
            } elseif (\class_exists(NyholmPsr17Factory::class)) {
                $psr17Factory = new NyholmPsr17Factory();
            } else {
                throw new \LogicException('You cannot use the "Symfony\\Component\\HttpClient\\Psr18Client" as no PSR-17 factories have been provided. Try running "composer require php-http/discovery psr/http-factory-implementation:*".');
            }
            $responseFactory ??= $psr17Factory;
            $streamFactory ??= $psr17Factory;
        }
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }
    public function withOptions(array $options) : static
    {
        $clone = clone $this;
        $clone->client = $clone->client->withOptions($options);
        return $clone;
    }
    public function sendRequest(RequestInterface $request) : ResponseInterface
    {
        try {
            $body = $request->getBody();
            if ($body->isSeekable()) {
                $body->seek(0);
            }
            $options = ['headers' => $request->getHeaders(), 'body' => $body->getContents()];
            if ('1.0' === $request->getProtocolVersion()) {
                $options['http_version'] = '1.0';
            }
            $response = $this->client->request($request->getMethod(), (string) $request->getUri(), $options);
            return HttplugWaitLoop::createPsr7Response($this->responseFactory, $this->streamFactory, $this->client, $response, \false);
        } catch (TransportExceptionInterface $e) {
            if ($e instanceof \InvalidArgumentException) {
                throw new Psr18RequestException($e, $request);
            }
            throw new Psr18NetworkException($e, $request);
        }
    }
    public function createRequest(string $method, $uri) : RequestInterface
    {
        if ($this->responseFactory instanceof RequestFactoryInterface) {
            return $this->responseFactory->createRequest($method, $uri);
        }
        if (\class_exists(Psr17FactoryDiscovery::class)) {
            return Psr17FactoryDiscovery::findRequestFactory()->createRequest($method, $uri);
        }
        if (\class_exists(Request::class)) {
            return new Request($method, $uri);
        }
        throw new \LogicException(\sprintf('You cannot use "%s()" as no PSR-17 factories have been found. Try running "composer require php-http/discovery psr/http-factory-implementation:*".', __METHOD__));
    }
    public function createStream(string $content = '') : StreamInterface
    {
        $stream = $this->streamFactory->createStream($content);
        if ($stream->isSeekable()) {
            $stream->seek(0);
        }
        return $stream;
    }
    public function createStreamFromFile(string $filename, string $mode = 'r') : StreamInterface
    {
        return $this->streamFactory->createStreamFromFile($filename, $mode);
    }
    public function createStreamFromResource($resource) : StreamInterface
    {
        return $this->streamFactory->createStreamFromResource($resource);
    }
    public function createUri(string $uri = '') : UriInterface
    {
        if ($this->responseFactory instanceof UriFactoryInterface) {
            return $this->responseFactory->createUri($uri);
        }
        if (\class_exists(Psr17FactoryDiscovery::class)) {
            return Psr17FactoryDiscovery::findUrlFactory()->createUri($uri);
        }
        if (\class_exists(Uri::class)) {
            return new Uri($uri);
        }
        throw new \LogicException(\sprintf('You cannot use "%s()" as no PSR-17 factories have been found. Try running "composer require php-http/discovery psr/http-factory-implementation:*".', __METHOD__));
    }
    public function reset() : void
    {
        if ($this->client instanceof ResetInterface) {
            $this->client->reset();
        }
    }
}
/**
 * @internal
 */
class Psr18NetworkException extends \RuntimeException implements NetworkExceptionInterface
{
    private RequestInterface $request;
    public function __construct(TransportExceptionInterface $e, RequestInterface $request)
    {
        parent::__construct($e->getMessage(), 0, $e);
        $this->request = $request;
    }
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}
/**
 * @internal
 */
class Psr18RequestException extends \InvalidArgumentException implements RequestExceptionInterface
{
    private RequestInterface $request;
    public function __construct(TransportExceptionInterface $e, RequestInterface $request)
    {
        parent::__construct($e->getMessage(), 0, $e);
        $this->request = $request;
    }
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}
