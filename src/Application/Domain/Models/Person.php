<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final readonly class Person implements ValueObject
{
    use ValueObjectBehavior;

    public function __construct(public Name $name, public Distance $distance)
    {
    }
}
