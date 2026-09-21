<?php

declare(strict_types=1);

namespace Test\Unit\Driven\Carrier\Repository\Records;

use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Exceptions\CarrierModalityNotSupported;
use CheapDelivery\Driven\Carrier\Repository\Records\CarrierRecordReader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CarrierRecordReaderTest extends TestCase
{
    public static function unreadableModalityProvider(): array
    {
        return [
            'Malformed JSON'      => ['costModality' => 'not json at all'],
            'Empty JSON object'   => ['costModality' => '{}'],
            'Unknown modality'    => ['costModality' => '{"modality":"Exponential"}'],
            'Non string modality' => ['costModality' => '{"modality":123}'],
            'Unknown condition'   => [
                'costModality' => '{"modality":"Partial",'
                    . '"costModality":{"modality":"Fixed","cost":2.10},'
                    . '"costCondition":{"name":"WeightBetween","weight":5.00}}'
            ],
            'Non string condition' => [
                'costModality' => '{"modality":"Partial",'
                    . '"costModality":{"modality":"Fixed","cost":2.10},'
                    . '"costCondition":{"name":123,"weight":5.00}}'
            ]
        ];
    }

    public function testRebuildsTheCarrierFromItsRecord(): void
    {
        /** @Given a stored carrier row */
        $record = [
            'id'           => '0193b3a1-0000-7000-8000-000000000001',
            'name'         => 'DHL',
            'costModality' => '{"modality":"Composite",'
                . '"modalityOne":{"cost":10,"modality":"Fixed"},'
                . '"modalityTwo":{"cost":0.05,"modality":"Linear"}}'
        ];

        /** @When it is read back */
        $carrier = new CarrierRecordReader()->toCarrier(record: $record);

        /** @Then the carrier prices the shipment as it was stored */
        $shipment = $carrier->quote(weight: Weight::from(value: 2.16), distance: Distance::from(value: 800.00));

        self::assertSame('DHL', $carrier->name->value);
        self::assertSame(96.40, $shipment->cost->toFloat());
    }

    #[DataProvider('unreadableModalityProvider')]
    public function testRefusesACarrierWhoseModalityCannotBeRead(string $costModality): void
    {
        /** @Given a stored carrier row this service cannot read */
        $record = [
            'id'           => '0193b3a1-0000-7000-8000-000000000001',
            'name'         => 'DHL',
            'costModality' => $costModality
        ];

        /** @Then the carrier is refused */
        $this->expectException(CarrierModalityNotSupported::class);
        $this->expectExceptionMessage('A registered carrier declares a cost modality this service does not support.');

        /** @When it is read back */
        new CarrierRecordReader()->toCarrier(record: $record);
    }
}
