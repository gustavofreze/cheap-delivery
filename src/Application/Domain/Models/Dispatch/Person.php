<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\ValueObject;
use CheapDelivery\Application\Domain\Models\Commons\ValueObjectBehavior;

final readonly class Person implements ValueObject
{
    use ValueObjectBehavior;

    private function __construct(public Name $name, public Distance $distance)
    {
    }

    public static function from(Name $name, Distance $distance): Person
    {
        return new Person(name: $name, distance: $distance);
    }
}
