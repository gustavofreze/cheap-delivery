<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use ArrayIterator;
use CheapDelivery\Application\Domain\Models\Commons\Factories\SomeThings;
use Iterator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @param mixed[] $elements
     * @param array $expected
     * @return void
     * @dataProvider itemsProvider
     */
    public function testNormalize(iterable $elements, array $expected): void
    {
        $actual = new SomeThings(elements: $elements);

        self::assertEquals($expected, $actual->all());
    }

    public static function itemsProvider(): array
    {
        return [
            'array'       => [
                'items'    => [1, 2, 3],
                'expected' => [1, 2, 3],
            ],
            'countable'   => [
                'items'    => new ArrayIterator([4, 5, 6]),
                'expected' => [4, 5, 6],
            ],
            'traversable' => [
                new class implements IteratorAggregate {
                    public function getIterator(): Iterator
                    {
                        yield 1;
                        yield 2;
                        yield 3;
                    }
                },
                'expected' => [1, 2, 3]
            ]
        ];
    }
}
