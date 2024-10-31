<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ScobyAnalyticsDeps\Symfony\Component\HttpClient\Response;

use ScobyAnalyticsDeps\Symfony\Contracts\HttpClient\ChunkInterface;
use ScobyAnalyticsDeps\Symfony\Contracts\HttpClient\ResponseInterface;
use ScobyAnalyticsDeps\Symfony\Contracts\HttpClient\ResponseStreamInterface;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class ResponseStream implements ResponseStreamInterface
{
    private \Generator $generator;
    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
    }
    public function key() : ResponseInterface
    {
        return $this->generator->key();
    }
    public function current() : ChunkInterface
    {
        return $this->generator->current();
    }
    public function next() : void
    {
        $this->generator->next();
    }
    public function rewind() : void
    {
        $this->generator->rewind();
    }
    public function valid() : bool
    {
        return $this->generator->valid();
    }
}
