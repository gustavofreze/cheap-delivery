<?php

namespace CheapDelivery\Driven\Shared\Database\MySql;

use CheapDelivery\Driven\Shared\Database\DatabaseFailure;
use RuntimeException;
use Throwable;

final class MySqlFailure extends RuntimeException implements DatabaseFailure
{
    public function __construct(private readonly string $error, private readonly Throwable $exception)
    {
        $template = 'MySQL operation <%s> failed.';
        parent::__construct(message: sprintf($template, $this->error));
    }

    public function trace(): string
    {
        $error = $this->exception->getMessage();
        $file = $this->exception->getFile();
        $line = $this->exception->getLine();

        $template = 'An error occurred <%s>, in the file <%s>, at line <%s>.';

        return sprintf($template, $error, $file, $line);
    }
}
