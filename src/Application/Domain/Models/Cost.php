<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final class Cost extends PositiveDecimal implements ValueObject
{
    use ValueObjectBehavior;
}
