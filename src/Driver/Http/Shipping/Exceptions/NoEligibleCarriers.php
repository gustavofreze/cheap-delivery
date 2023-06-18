<?php

namespace CheapDelivery\Driver\Http\Shipping\Exceptions;

use CheapDelivery\Driver\Http\Shared\HttpException;
use Exception;
use TinyBlocks\Http\HttpCode;

final class NoEligibleCarriers extends Exception implements HttpException
{
    public function __construct()
    {
        parent::__construct(
            message: 'There are no eligible carriers for the shipment.',
            code: HttpCode::UNPROCESSABLE_ENTITY->value
        );
    }

    public function getErrors(): array
    {
        return ['carrier' => $this->message];
    }
}
