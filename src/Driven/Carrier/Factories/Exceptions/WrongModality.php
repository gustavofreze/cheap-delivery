<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Exceptions;

use LogicException;

final class WrongModality extends LogicException
{
    public function __construct(string $invalid, string $expected)
    {
        parent::__construct(message: sprintf('Invalid %s modality. Modality should be %s', $invalid, $expected));
    }
}
