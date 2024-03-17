<?php

namespace CheapDelivery\Application\Domain\Models;

final readonly class Person
{
    public function __construct(public Name $name, public Distance $distance)
    {
    }
}
