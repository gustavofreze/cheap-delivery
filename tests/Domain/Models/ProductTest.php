<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreationOfProductWithValidValues(): void
    {
        $product = new Product(name: new Name(value: 'Notebook'), weight: new Weight(value: 5.00));

        self::assertEquals('Notebook', $product->getName()->getValue());
        self::assertEquals(5.00, $product->getWeight()->getValue());
    }

    /**
     * @dataProvider invalidProduct
     */
    public function testCreationOfProductWithInvalidValues(string $name, float $weight, string $message): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage($message);

        new Product(name: new Name(value: $name), weight: new Weight(value: $weight));
    }

    public function invalidProduct(): array
    {
        return [
            [
                'name'    => 'Notebook',
                'weight'  => 0.00,
                'message' => 'Weight cannot be zero or negative'
            ],
            [
                'name'    => '',
                'weight'  => 5.00,
                'message' => 'Name cannot be empty'
            ]
        ];
    }
}
