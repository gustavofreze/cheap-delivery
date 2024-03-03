<?php

namespace CheapDelivery\Query\Dispatch;

use CheapDelivery\Factories\Request;
use CheapDelivery\Query\Dispatch\Database\Facade;
use CheapDelivery\Query\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use TinyBlocks\Http\HttpCode;

class ResourceTest extends TestCase
{
    private Resource $resource;

    private MockObject $queryBuilder;

    protected function setUp(): void
    {
        $this->queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['applyFilters'])
            ->getMock();

        $facade = new Facade(queryBuilder: $this->queryBuilder);
        $this->resource = new Resource(facade: $facade);
    }

    public function testExceptionWhenQueryFailure(): void
    {
        /** @Given that I have a request to fetch the dispatches */
        $request = Request::getFrom(parameters: []);

        /** @And that an error occurs when executing the query */
        $this->queryBuilder->expects(self::once())
            ->method('applyFilters')
            ->willThrowException(new RuntimeException('Database error.'));

        /** @When I execute the request */
        $actual = $this->resource->handle(request: $request);

        /** @Then a response indicating an error should be returned */
        $expected = ['error' => 'Query failed. Reason: <Database error.>.'];

        self::assertEquals(HttpCode::INTERNAL_SERVER_ERROR->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }
}
