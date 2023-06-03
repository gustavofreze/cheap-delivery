<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testCreationOfPersonWithValidValues(): void
    {
        $person = new Person(new Name(value: 'Gustavo'), new Distance(value: 20.00));

        self::assertEquals('Gustavo', $person->getName()->getValue());
        self::assertEquals(20.00, $person->getDistance()->getValue());
    }

    /**
     * @dataProvider invalidPerson
     */
    public function testCreationOfPersonWithInvalidValues(string $name, float $distance, string $message): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage($message);

        new Person(name: new Name(value: $name), distance: new Distance(value: $distance));
    }

    public function invalidPerson(): array
    {
        return [
            [
                'name'     => 'Gustavo',
                'distance' => 0.00,
                'message'  => 'Distance cannot be zero or negative'
            ],
            [
                'name'     => '',
                'distance' => 1.00,
                'message'  => 'Name cannot be empty'
            ]
        ];
    }
}
