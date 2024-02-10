<?php

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class NonPositiveValue extends DomainException
{
    public function __construct(string $class, float $value)
    {
        $template = '%s cannot be zero or negative. Invalid value <%s>.';
        $className = $this->getClassNameFrom(class: $class);
        parent::__construct(message: sprintf($template, $className, $value));
    }

    private function getClassNameFrom(string $class): string
    {
        $parts = explode('\\', $class);
        return end($parts);
    }
}
