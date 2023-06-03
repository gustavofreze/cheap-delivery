<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

final class Product
{
    public function __construct(private readonly Name $name, private readonly Weight $weight)
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
