<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Records;

use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Exceptions\CarrierModalityNotSupported;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownCondition;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownModality;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\CostModalityGenericFactory;

final readonly class CarrierRecordReader
{
    public function toCarrier(array $record): Carrier
    {
        $modality = (array)json_decode((string)$record['costModality'], true);

        try {
            $costModality = new CostModalityGenericFactory(costModality: $modality)->build();
        } catch (UnknownCondition | UnknownModality $cause) {
            throw new CarrierModalityNotSupported(cause: $cause);
        }

        return Carrier::from(name: Name::from(value: (string)$record['name']), modality: $costModality);
    }
}
