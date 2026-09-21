<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class NonPositiveValue extends DomainException
{
    public function __construct(string $type, float $value)
    {
        $template = '%s cannot be zero or negative. Invalid value <%s>.';

        parent::__construct(message: sprintf($template, $type, $value));
    }
}
