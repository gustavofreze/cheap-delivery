<?php

namespace CheapDelivery\Application\Domain\Events;

use ReflectionClass;

trait EventCapabilities
{
    public function getType(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    abstract public function getRevision(): int;
}
