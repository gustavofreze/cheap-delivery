<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final readonly class Name implements ValueObject
{
    use ValueObjectBehavior;

    private const MAXIMUM_LENGTH = 255;

    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new EmptyName();
        }

        $currentLength = strlen($this->value);

        if ($currentLength > self::MAXIMUM_LENGTH) {
            throw new NameTooLong(current: $currentLength, maximum: self::MAXIMUM_LENGTH);
        }
    }
}
