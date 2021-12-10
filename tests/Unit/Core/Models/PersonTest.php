<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Person;
use LogicException;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testCreationOfPersonWithValidValues(): void
    {
        $person = new Person(new Name('Gustavo'), new Distance(20.00));

        self::assertEquals('Gustavo', $person->getName()->getValue());
        self::assertEquals(20.00, $person->getDistance()->getValue());
    }

    /**
     * @param string $name
     * @param float $distance
     * @param string $message
     * @return void
     * @dataProvider invalidPerson
     */
    public function testCreationOfPersonWithInvalidValues(string $name, float $distance, string $message): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage($message);

        new Person(new Name($name), new Distance($distance));
    }

    public function invalidPerson(): array
    {
        return [
            [
                'name' => 'Gustavo',
                'distance' => 0.00,
                'message' => 'Distance cannot be zero or negative'
            ],
            [
                'name' => '',
                'distance' => 1.00,
                'message' => 'Name cannot be empty'
            ],
        ];
    }
}
