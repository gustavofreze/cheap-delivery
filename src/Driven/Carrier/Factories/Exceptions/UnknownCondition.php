<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Exceptions;

use LogicException;

final class UnknownCondition extends LogicException
{
    public function __construct(string $invalid)
    {
        $template = 'Unknown %s condition';
        parent::__construct(message: sprintf($template, $invalid));
    }
}
