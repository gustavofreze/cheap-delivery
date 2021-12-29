<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Actions\Shipping;

use CheapDelivery\Driver\Http\Exceptions\InvalidRequestPayload;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;

final class CalculateShippingValidator
{
    public function validate(mixed $request): void
    {
        try {
            Validator::key(
                'person',
                Validator::key('name', Validator::stringType())->key('distance', Validator::floatType())
            )->key(
                'product',
                Validator::key('name', Validator::stringType())->key('weight', Validator::floatType())
            )->assert($request);
        } catch (ValidationException $exception) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            throw new InvalidRequestPayload($exception->getMessages());
        }
    }
}
