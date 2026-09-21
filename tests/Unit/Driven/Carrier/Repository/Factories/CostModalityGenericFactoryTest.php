<?php

declare(strict_types=1);

namespace Test\Unit\Driven\Carrier\Repository\Factories;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\FixedCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\LinearCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\PartialCost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Driven\Carrier\Repository\Factories\Conditions\CostConditionGenericFactory;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownCondition;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownModality;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\CompositeCostFactory;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\CostModalityGenericFactory;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\FixedCostFactory;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\LinearCostFactory;
use CheapDelivery\Driven\Carrier\Repository\Factories\Modalities\PartialCostFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CostModalityGenericFactoryTest extends TestCase
{
    public static function specificFactoryProvider(): array
    {
        return [
            'Fixed'     => ['factory' => FixedCostFactory::class, 'expected' => 'Fixed'],
            'Linear'    => ['factory' => LinearCostFactory::class, 'expected' => 'Linear'],
            'Partial'   => ['factory' => PartialCostFactory::class, 'expected' => 'Partial'],
            'Composite' => ['factory' => CompositeCostFactory::class, 'expected' => 'Composite']
        ];
    }

    public function testBuildsAFixedModality(): void
    {
        /** @Given a stored fixed modality */
        $stored = ['modality' => 'Fixed', 'cost' => 10.00];

        /** @When it is read back */
        $modality = new CostModalityGenericFactory(costModality: $stored)->build();

        /** @Then the fixed modality is rebuilt */
        self::assertInstanceOf(FixedCost::class, $modality);
    }

    public function testBuildsALinearModality(): void
    {
        /** @Given a stored linear modality */
        $stored = ['modality' => 'Linear', 'cost' => 0.05];

        /** @When it is read back */
        $modality = new CostModalityGenericFactory(costModality: $stored)->build();

        /** @Then the linear modality is rebuilt */
        self::assertInstanceOf(LinearCost::class, $modality);
    }

    public function testBuildsACompositeModality(): void
    {
        /** @Given a stored composite modality */
        $stored = [
            'modality'     => 'Composite',
            'modalityOne'  => ['modality' => 'Fixed', 'cost' => 10.00],
            'modalityTwo'  => ['modality' => 'Linear', 'cost' => 0.05]
        ];

        /** @When it is read back */
        $modality = new CostModalityGenericFactory(costModality: $stored)->build();

        /** @Then the composite prices the shipment through both parts */
        self::assertInstanceOf(CompositeCost::class, $modality);
        self::assertSame(
            96.40,
            $modality->calculate(weight: Weight::from(value: 2.16), distance: Distance::from(value: 800.00))->toFloat()
        );
    }

    public function testBuildsAPartialModality(): void
    {
        /** @Given a stored partial modality bound to a weight band */
        $stored = [
            'modality'      => 'Partial',
            'costModality'  => ['modality' => 'Fixed', 'cost' => 2.10],
            'costCondition' => ['name' => 'WeightSmallerThan', 'weight' => 5.00]
        ];

        /** @When it is read back */
        $modality = new CostModalityGenericFactory(costModality: $stored)->build();

        /** @Then the band decides whether the shipment is priced */
        self::assertInstanceOf(PartialCost::class, $modality);
        self::assertSame(
            2.10,
            $modality->calculate(weight: Weight::from(value: 2.16), distance: Distance::from(value: 10.00))->toFloat()
        );
        self::assertNull(
            $modality->calculate(weight: Weight::from(value: 7.50), distance: Distance::from(value: 10.00))
        );
    }

    public function testBuildsAGreaterThanOrEqualCondition(): void
    {
        /** @Given a stored heavy band condition */
        $stored = ['name' => 'WeightGreaterThanOrEqual', 'weight' => 5.00];

        /** @When it is read back */
        $condition = new CostConditionGenericFactory(costCondition: $stored)->build();

        /** @Then it applies from the threshold up */
        self::assertTrue($condition->apply(weight: Weight::from(value: 5.00)));
        self::assertFalse($condition->apply(weight: Weight::from(value: 4.99)));
    }

    public function testRefusesAnUnknownModality(): void
    {
        /** @Given a stored modality this service does not know */
        $stored = ['modality' => 'Exponential'];

        /** @Then the modality is refused, naming what it read */
        $this->expectException(UnknownModality::class);
        $this->expectExceptionMessage('Unknown <Exponential> modality.');

        /** @When it is read back */
        new CostModalityGenericFactory(costModality: $stored)->build();
    }

    public function testRefusesAnUnknownCondition(): void
    {
        /** @Given a stored condition this service does not know */
        $stored = ['name' => 'WeightBetween', 'weight' => 5.00];

        /** @Then the condition is refused, naming what it read */
        $this->expectException(UnknownCondition::class);
        $this->expectExceptionMessage('Unknown <WeightBetween> condition.');

        /** @When it is read back */
        new CostConditionGenericFactory(costCondition: $stored)->build();
    }

    public function testRefusesAConditionWithoutItsWeight(): void
    {
        /** @Given a stored condition whose weight was never written */
        $stored = ['name' => 'WeightSmallerThan'];

        /** @Then the threshold is refused */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage('Weight cannot be zero or negative.');

        /** @When it is read back */
        new CostConditionGenericFactory(costCondition: $stored)->build();
    }

    public function testRefusesAModalityThatDoesNotMatchItsFactory(): void
    {
        /** @Given a stored linear modality handed to the fixed factory */
        $stored = ['modality' => 'Linear', 'cost' => 0.05];

        /** @Then the modality is refused, naming what it read and what it expected */
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage('Invalid <Linear> modality. Modality should be <Fixed>.');

        /** @When it is read back */
        new FixedCostFactory(costModality: $stored);
    }

    #[DataProvider('specificFactoryProvider')]
    public function testEveryFactoryRefusesANonStringModality(string $factory, string $expected): void
    {
        /** @Given a stored modality that is not even text */
        $stored = ['modality' => 123];

        /** @Then the modality is refused, naming what it read and what it expected */
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage(sprintf('Invalid <123> modality. Modality should be <%s>.', $expected));

        /** @When it is read back */
        new $factory(costModality: $stored);
    }
}
