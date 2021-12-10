<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Product;
use CheapDelivery\Core\Models\Weight;
use LogicException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreationOfProductWithValidValues(): void
    {
        $product = new Product(new Name('Notebook'), new Weight(5.00));

        self::assertEquals('Notebook', $product->getName()->getValue());
        self::assertEquals(5.00, $product->getWeight()->getValue());
    }

    /**
     * @param string $name
     * @param float $weight
     * @param string $message
     * @return void
     * @dataProvider invalidProduct
     */
    public function testCreationOfProductWithInvalidValues(string $name, float $weight, string $message): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage($message);

        new Product(new Name($name), new Weight($weight));
    }

    public function invalidProduct(): array
    {
        return [
            [
                'name' => 'Notebook',
                'weight' => 0.00,
                'message' => 'Weight cannot be zero or negative'
            ],
            [
                'name' => '',
                'weight' => 5.00,
                'message' => 'Name cannot be empty'
            ],
        ];
    }
}
