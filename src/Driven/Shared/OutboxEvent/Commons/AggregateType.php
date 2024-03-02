<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use ReflectionClass;
use ReflectionException;

final class AggregateType
{
    private function __construct(public string $value)
    {
    }

    /**
     * @param class-string $class
     * @return AggregateType
     * @throws ReflectionException
     */
    public static function from(string $class): AggregateType
    {
        return new AggregateType(value: (new ReflectionClass(objectOrClass: $class))->getShortName());
    }
}
