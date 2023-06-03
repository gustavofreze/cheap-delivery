<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

final class Person
{
    public function __construct(private readonly Name $name, private readonly Distance $distance)
    {
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getDistance(): Distance
    {
        return $this->distance;
    }
}
