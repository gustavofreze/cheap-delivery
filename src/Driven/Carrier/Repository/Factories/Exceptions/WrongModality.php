<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions;

use LogicException;

final class WrongModality extends LogicException
{
    public function __construct(string $invalid, string $expected)
    {
        $template = 'Invalid <%s> modality. Modality should be <%s>.';
        parent::__construct(message: sprintf($template, $invalid, $expected));
    }
}
