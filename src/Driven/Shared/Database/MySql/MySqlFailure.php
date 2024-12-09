<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\Database\MySql;

use CheapDelivery\Driven\Shared\Database\DatabaseFailure;
use RuntimeException;
use Throwable;

final class MySqlFailure extends RuntimeException implements DatabaseFailure
{
    public function __construct(private readonly string $error, private readonly Throwable $exception)
    {
        $template = 'MySQL operation <%s> failed. Reason: <%s>.';

        parent::__construct(message: sprintf($template, $this->error, $this->exception->getMessage()));
    }
}
