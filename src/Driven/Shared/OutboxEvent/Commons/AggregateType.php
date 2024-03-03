<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use ReflectionClass;

final class AggregateType
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $class): AggregateType
    {
        return new AggregateType(value: (new ReflectionClass(objectOrClass: $class))->getShortName());
    }
}
