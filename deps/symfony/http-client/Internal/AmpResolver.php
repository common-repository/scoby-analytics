<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ScobyAnalyticsDeps\Symfony\Component\HttpClient\Internal;

use ScobyAnalyticsDeps\Amp\Dns;
use ScobyAnalyticsDeps\Amp\Dns\Record;
use ScobyAnalyticsDeps\Amp\Promise;
use ScobyAnalyticsDeps\Amp\Success;
/**
 * Handles local overrides for the DNS resolver.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class AmpResolver implements Dns\Resolver
{
    private array $dnsMap;
    public function __construct(array &$dnsMap)
    {
        $this->dnsMap =& $dnsMap;
    }
    public function resolve(string $name, ?int $typeRestriction = null) : Promise
    {
        if (!isset($this->dnsMap[$name]) || !\in_array($typeRestriction, [Record::A, null], \true)) {
            return Dns\resolver()->resolve($name, $typeRestriction);
        }
        return new Success([new Record($this->dnsMap[$name], Record::A, null)]);
    }
    public function query(string $name, int $type) : Promise
    {
        if (!isset($this->dnsMap[$name]) || Record::A !== $type) {
            return Dns\resolver()->query($name, $type);
        }
        return new Success([new Record($this->dnsMap[$name], Record::A, null)]);
    }
}
