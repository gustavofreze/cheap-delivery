<?php

namespace CheapDelivery\Driver\Http\Shared\Exceptions;

use CheapDelivery\Driver\Http\Shared\HttpException;
use RuntimeException;
use TinyBlocks\Http\HttpCode;

final class InvalidRequest extends RuntimeException implements HttpException
{
    public function __construct(private readonly array $errors)
    {
        parent::__construct(code: HttpCode::UNPROCESSABLE_ENTITY->value);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
