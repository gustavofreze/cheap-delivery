<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions;

use LogicException;

final class UnknownModality extends LogicException
{
    public function __construct(string $invalid)
    {
        $template = 'Unknown <%s> modality.';
        parent::__construct(message: sprintf($template, $invalid));
    }
}
