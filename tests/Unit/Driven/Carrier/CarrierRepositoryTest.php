<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Driven\Carrier;

use CheapDelivery\Driven\Carrier\CarrierRepository;
use CheapDelivery\Driven\Carrier\Factories\Modalities\CostModalityFactory;
use CheapDelivery\Driven\Database\NoSqlDatabase;
use CheapDelivery\Driven\Database\NoSqlDatabaseEngine;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class CarrierRepositoryTest extends TestCase
{
    private NoSqlDatabase $noSqlDatabase;

    protected function setUp(): void
    {
        $this->noSqlDatabase = $this->createMock(NoSqlDatabaseEngine::class);
    }

    public function testWhenFindAllReturnsCarrierCollection(): void
    {
        $result = new BSONDocument([
            'name' => 'FedEx',
            'costModality' => new BSONDocument([
                'modality' => CostModalityFactory::COMPOSITE,
                'modalityOne' => new BSONDocument(['cost' => 44.10, 'modality' => CostModalityFactory::FIXED]),
                'modalityTwo' => new BSONDocument(['cost' => 10.12, 'modality' => CostModalityFactory::LINEAR])
            ]),
        ]);

        $this->noSqlDatabase->expects(self::once())
            ->method('find')
            ->will(self::returnValue([$result]));

        $carrierRepository = new CarrierRepository($this->noSqlDatabase);
        $carriers = $carrierRepository->findAll();

        self::assertCount(1, $carriers);
    }

    public function testWhenFindAllReturnsAnEmptyCollection(): void
    {
        $this->noSqlDatabase->expects(self::once())
            ->method('find')
            ->will(self::returnValue([]));

        $carrierRepository = new CarrierRepository($this->noSqlDatabase);
        $carriers = $carrierRepository->findAll();

        self::assertCount(0, $carriers);
    }
}
