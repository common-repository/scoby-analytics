<?php

declare (strict_types=1);
namespace ScobyAnalyticsDeps\RebelCode\Psr7;

use ScobyAnalyticsDeps\Psr\Http\Message\MessageInterface;
use ScobyAnalyticsDeps\Psr\Http\Message\StreamInterface;
/**
 * Trait implementing functionality common to requests and responses.
 */
trait MessageTrait
{
    /** @var array<string, string[]> Map of all registered headers, as original name => array of values */
    private $headers = [];
    /** @var array<string, string> Map of lowercase header name => original name at registration */
    private $headerNames = [];
    /** @var string */
    private $protocol = '1.1';
    /** @var StreamInterface|null */
    private $stream;
    public function getProtocolVersion() : string
    {
        return $this->protocol;
    }
    public function withProtocolVersion($version) : MessageInterface
    {
        if ($this->protocol === $version) {
            return $this;
        }
        $new = clone $this;
        $new->protocol = $version;
        return $new;
    }
    public function getHeaders() : array
    {
        return $this->headers;
    }
    public function hasHeader($name) : bool
    {
        return isset($this->headerNames[\strtolower($name)]);
    }
    public function getHeader($name) : array
    {
        $name = \strtolower($name);
        if (!isset($this->headerNames[$name])) {
            return [];
        }
        $name = $this->headerNames[$name];
        return $this->headers[$name];
    }
    public function getHeaderLine($name) : string
    {
        return \implode(', ', $this->getHeader($name));
    }
    public function withHeader($name, $value) : MessageInterface
    {
        $this->assertHeader($name);
        $value = $this->normalizeHeaderValue($value);
        $normalized = \strtolower($name);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $name;
        $new->headers[$name] = $value;
        return $new;
    }
    public function withAddedHeader($name, $value) : MessageInterface
    {
        $this->assertHeader($name);
        $value = $this->normalizeHeaderValue($value);
        $normalized = \strtolower($name);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $name = $this->headerNames[$normalized];
            $new->headers[$name] = \array_merge($this->headers[$name], $value);
        } else {
            $new->headerNames[$normalized] = $name;
            $new->headers[$name] = $value;
        }
        return $new;
    }
    public function withoutHeader($name) : MessageInterface
    {
        $normalized = \strtolower($name);
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }
        $name = $this->headerNames[$normalized];
        $new = clone $this;
        unset($new->headers[$name], $new->headerNames[$normalized]);
        return $new;
    }
    public function getBody() : StreamInterface
    {
        if (!$this->stream) {
            $this->stream = Utils::streamFor('');
        }
        return $this->stream;
    }
    public function withBody(StreamInterface $body) : MessageInterface
    {
        if ($body === $this->stream) {
            return $this;
        }
        $new = clone $this;
        $new->stream = $body;
        return $new;
    }
    /**
     * @param array<string|int, string|string[]> $headers
     */
    private function setHeaders(array $headers) : void
    {
        $this->headerNames = $this->headers = [];
        foreach ($headers as $header => $value) {
            if (\is_int($header)) {
                // Numeric array keys are converted to int by PHP but having a header name '123' is not forbidden by the spec
                // and also allowed in withHeader(). So we need to cast it to string again for the following assertion to pass.
                $header = (string) $header;
            }
            $this->assertHeader($header);
            $value = $this->normalizeHeaderValue($value);
            $normalized = \strtolower($header);
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = \array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }
    /**
     * @param mixed $value
     *
     * @return string[]
     */
    private function normalizeHeaderValue($value) : array
    {
        if (!\is_array($value)) {
            return $this->trimHeaderValues([$value]);
        }
        if (\count($value) === 0) {
            throw new \InvalidArgumentException('Header value can not be an empty array.');
        }
        return $this->trimHeaderValues($value);
    }
    /**
     * Trims whitespace from the header values.
     *
     * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
     *
     * header-field = field-name ":" OWS field-value OWS
     * OWS          = *( SP / HTAB )
     *
     * @param mixed[] $values Header values
     *
     * @return string[] Trimmed header values
     *
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     */
    private function trimHeaderValues(array $values) : array
    {
        return \array_map(function ($value) {
            if (!\is_scalar($value) && null !== $value) {
                throw new \InvalidArgumentException(\sprintf('Header value must be scalar or null but %s provided.', \is_object($value) ? \get_class($value) : \gettype($value)));
            }
            return \trim((string) $value, " \t");
        }, \array_values($values));
    }
    /**
     * @see https://tools.ietf.org/html/rfc7230#section-3.2
     *
     * @param mixed $header
     */
    private function assertHeader($header) : void
    {
        if (!\is_string($header)) {
            throw new \InvalidArgumentException(\sprintf('Header name must be a string but %s provided.', \is_object($header) ? \get_class($header) : \gettype($header)));
        }
        if (!\preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/', $header)) {
            throw new \InvalidArgumentException(\sprintf('"%s" is not valid header name', $header));
        }
    }
}
