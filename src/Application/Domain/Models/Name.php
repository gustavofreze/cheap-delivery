<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;

final readonly class Name
{
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
