<?php

namespace CheapDelivery\Application\Domain\Events;

use ReflectionClass;

trait EventCapabilities
{
    public function type(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    abstract public function revision(): int;
}
