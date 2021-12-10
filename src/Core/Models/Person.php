<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

final class Person
{
    public function __construct(private Name $name, private Distance $distance)
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
