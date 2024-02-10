<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment;

use CheapDelivery\Application\Commands\CalculateShipment;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Person;
use CheapDelivery\Application\Domain\Models\Product;
use CheapDelivery\Application\Domain\Models\Weight;

final readonly class Request
{
    public function __construct(private array $payload)
    {
    }

    public function toCommand(): CalculateShipment
    {
        $person = $this->payload['person'];
        $product = $this->payload['product'];

        return new CalculateShipment(
            person: new Person(
                name: new Name(value: $person['name']),
                distance: new Distance(value: $person['distance'])
            ),
            product: new Product(
                name: new Name(value: $product['name']),
                weight: new Weight(value: $product['weight'])
            )
        );
    }
}
