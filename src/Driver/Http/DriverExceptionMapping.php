<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;
use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use CheapDelivery\Application\Exceptions\CarrierModalityNotSupported;
use TinyBlocks\Http\Code;
use TinyBlocks\Http\ErrorHandler\ExceptionMapping;
use TinyBlocks\Http\ErrorHandler\ExceptionMappingTable;
use TinyBlocks\Http\ErrorHandler\MappedError;

final readonly class DriverExceptionMapping implements ExceptionMapping
{
    public function mappings(): ExceptionMappingTable
    {
        return ExceptionMappingTable::create()
            ->when(exceptionClass: NoCarriersAvailable::class)
            ->mapsTo(
                code: 'NO_CARRIERS_AVAILABLE',
                status: Code::NOT_FOUND->value,
                message: 'There are no carriers available for dispatch.'
            )
            ->when(exceptionClass: NoEligibleCarriers::class)
            ->mapsTo(
                code: 'NO_ELIGIBLE_CARRIERS',
                status: Code::CONFLICT->value,
                message: 'There are no eligible carriers for the dispatch.'
            )
            ->when(exceptionClass: WeightOutOfRange::class)
            ->resolvesWith(resolver: static fn(WeightOutOfRange $error): MappedError => new MappedError(
                code: 'WEIGHT_OUT_OF_RANGE',
                status: Code::UNPROCESSABLE_ENTITY->value,
                message: $error->getMessage()
            ))
            ->when(exceptionClass: DistanceOutOfRange::class)
            ->resolvesWith(resolver: static fn(DistanceOutOfRange $error): MappedError => new MappedError(
                code: 'DISTANCE_OUT_OF_RANGE',
                status: Code::UNPROCESSABLE_ENTITY->value,
                message: $error->getMessage()
            ))
            ->when(exceptionClass: NonPositiveValue::class)
            ->resolvesWith(resolver: static fn(NonPositiveValue $error): MappedError => new MappedError(
                code: 'NON_POSITIVE_VALUE',
                status: Code::UNPROCESSABLE_ENTITY->value,
                message: $error->getMessage()
            ))
            ->whenAny(exceptionClasses: [EmptyName::class, NameTooLong::class])
            ->resolvesWith(resolver: static fn(EmptyName|NameTooLong $error): MappedError => new MappedError(
                code: 'INVALID_NAME',
                status: Code::UNPROCESSABLE_ENTITY->value,
                message: $error->getMessage()
            ))
            ->when(exceptionClass: CarrierModalityNotSupported::class)
            ->mapsTo(
                code: 'CARRIER_MODALITY_NOT_SUPPORTED',
                status: Code::UNPROCESSABLE_ENTITY->value,
                message: 'A registered carrier declares a cost modality this service does not support.'
            )
            ->when(exceptionClass: InvalidRequest::class)
            ->resolvesWith(resolver: static fn(InvalidRequest $error): MappedError => new MappedError(
                code: 'INVALID_REQUEST',
                status: Code::UNPROCESSABLE_ENTITY->value,
                message: implode(', ', $error->messages)
            ));
    }
}
