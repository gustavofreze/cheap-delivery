<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use ReflectionClass;

final readonly class AggregateType
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $class): AggregateType
    {
        return new AggregateType(value: (new ReflectionClass(objectOrClass: $class))->getShortName());
    }
}
