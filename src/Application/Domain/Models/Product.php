<?php

namespace CheapDelivery\Application\Domain\Models;

final readonly class Product
{
    public function __construct(public Name $name, public Weight $weight)
    {
    }
}
