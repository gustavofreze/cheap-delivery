<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Environment;

use RuntimeException;

final class EnvironmentAdapter implements Environment
{
    public function get(string $variable): string
    {
        $value = getenv($variable);
        $template = 'Environment variable %s is missing';

        return is_string($value) ? $value : throw new RuntimeException(message: sprintf($template, $variable));
    }
}
