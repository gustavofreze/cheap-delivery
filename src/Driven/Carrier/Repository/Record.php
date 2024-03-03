<?php

namespace CheapDelivery\Driven\Carrier\Repository;

use CheapDelivery\Application\Domain\Models\Carrier;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\CostModalityGenericFactory;

final readonly class Record
{
    public function __construct(private array $value)
    {
    }

    public static function from(array $value): Record
    {
        return new Record(value: $value);
    }

    public function toCarrier(): Carrier
    {
        $costModality = (array)json_decode($this->value['costModality'], true);

        return new Carrier(
            name: new Name(value: $this->value['name']),
            costModality: (new CostModalityGenericFactory(costModality: $costModality))->build()
        );
    }
}
