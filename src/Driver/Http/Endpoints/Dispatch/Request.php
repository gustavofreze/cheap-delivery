<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Domain\Models\Dispatch\DispatchId;
use CheapDelivery\Application\Domain\Models\Dispatch\Person;
use CheapDelivery\Application\Domain\Models\Dispatch\Product;
use CheapDelivery\Driver\Http\InvalidRequest;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\ValidatorBuilder;

final readonly class Request
{
    public function __construct(private array $payload)
    {
        try {
            $name = ValidatorBuilder::stringType()->length(ValidatorBuilder::between(1, 255));
            $number = ValidatorBuilder::numericVal()->positive();

            $person = ValidatorBuilder::arrayType()->key('name', $name)->key('distance', $number);
            $product = ValidatorBuilder::arrayType()->key('name', $name)->key('weight', $number);

            ValidatorBuilder::arrayType()
                ->key('person', $person)
                ->key('product', $product)
                ->assert($this->payload);
        } catch (ValidationException $exception) {
            throw new InvalidRequest(messages: $exception->getMessages());
        }
    }

    public function toCommand(): DispatchWithLowestCost
    {
        $person = $this->payload['person'];
        $product = $this->payload['product'];

        return new DispatchWithLowestCost(
            id: DispatchId::generate(),
            person: Person::from(
                name: Name::from(value: (string)$person['name']),
                distance: Distance::from(value: (float)$person['distance'])
            ),
            product: Product::from(
                name: Name::from(value: (string)$product['name']),
                weight: Weight::from(value: (float)$product['weight'])
            )
        );
    }
}
