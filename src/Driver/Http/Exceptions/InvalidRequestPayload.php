<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Exceptions;

use CheapDelivery\Driver\Http\ActionException;
use CheapDelivery\Driver\Http\HttpCode;
use Exception;

final class InvalidRequestPayload extends Exception implements ActionException
{
    public function __construct(private array $errors)
    {
        parent::__construct(code: HttpCode::BAD_REQUEST);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
