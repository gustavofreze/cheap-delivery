<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

final class Product
{
    public function __construct(private Name $name, private Weight $weight)
    {
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }
}
