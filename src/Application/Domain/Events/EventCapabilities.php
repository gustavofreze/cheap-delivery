<?php

declare(strict_types=1);

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
