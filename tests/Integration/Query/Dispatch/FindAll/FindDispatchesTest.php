<?php

declare(strict_types=1);

namespace Test\Integration\Query\Dispatch\FindAll;

use CheapDelivery\Query\Dispatch\FindAll\Http\FindDispatches;
use Test\Integration\DispatchRequests;
use Test\Integration\IntegrationTestCase;
use TinyBlocks\Http\Code;

final class FindDispatchesTest extends IntegrationTestCase
{
    private const string FIRST_ID = '0193b3a1-0000-7000-8000-000000000001';
    private const string SECOND_ID = '0193b3a1-0000-7000-8000-000000000002';
    private const string THIRD_ID = '0193b3a1-0000-7000-8000-000000000003';

    protected function setUp(): void
    {
        $this->fixtures()->insertDispatch(
            id: FindDispatchesTest::FIRST_ID,
            cost: 96.40,
            createdAt: '2026-09-21 10:00:00.000000',
            carrierName: 'DHL'
        );
        $this->fixtures()->insertDispatch(
            id: FindDispatchesTest::SECOND_ID,
            cost: 4.94,
            createdAt: '2026-09-21 11:00:00.000000',
            carrierName: 'Loggi'
        );
        $this->fixtures()->insertDispatch(
            id: FindDispatchesTest::THIRD_ID,
            cost: 12.30,
            createdAt: '2026-09-21 12:00:00.000000',
            carrierName: 'FedEx'
        );
    }

    public function testListsTheDispatchesMostRecentFirst(): void
    {
        /** @Given a request for the dispatch listing */
        $request = DispatchRequests::get(path: '/dispatches');

        /** @When the listing is read */
        $actual = $this->get(FindDispatches::class)->handle($request);
        $body = (array)json_decode($actual->getBody()->__toString(), true);

        /** @Then the page carries every dispatch, most recent first */
        self::assertSame(Code::OK->value, $actual->getStatusCode());
        self::assertSame(
            [FindDispatchesTest::THIRD_ID, FindDispatchesTest::SECOND_ID, FindDispatchesTest::FIRST_ID],
            array_column($body['data'], 'id')
        );
        self::assertFalse($body['meta']['has_next']);
    }

    public function testCarriesTheNextCursorWhenThePageIsFull(): void
    {
        /** @Given a request for a page of one dispatch */
        $request = DispatchRequests::get(path: '/dispatches?page%5Bsize%5D=1');

        /** @When the listing is read */
        $actual = $this->get(FindDispatches::class)->handle($request);
        $body = (array)json_decode($actual->getBody()->__toString(), true);

        /** @Then the page carries the next cursor and the RFC 8288 link header */
        self::assertTrue($body['meta']['has_next']);
        self::assertArrayHasKey('next', $body['links']);
        self::assertStringContainsString('rel="next"', $actual->getHeaderLine('Link'));
    }

    public function testWalksTheCursorToTheFollowingPage(): void
    {
        /** @Given the first page of one dispatch */
        $first = $this->get(FindDispatches::class)->handle(
            DispatchRequests::get(path: '/dispatches?page%5Bsize%5D=1')
        );
        $firstBody = (array)json_decode($first->getBody()->__toString(), true);

        /** @When the next cursor is followed */
        $second = $this->get(FindDispatches::class)->handle(
            DispatchRequests::get(path: $firstBody['links']['next'])
        );
        $secondBody = (array)json_decode($second->getBody()->__toString(), true);

        /** @Then the following dispatch is answered */
        self::assertSame(FindDispatchesTest::THIRD_ID, $firstBody['data'][0]['id']);
        self::assertSame(FindDispatchesTest::SECOND_ID, $secondBody['data'][0]['id']);
    }

    public function testFiltersByCarrierName(): void
    {
        /** @Given a request filtered by carrier */
        $request = DispatchRequests::get(path: '/dispatches?filter=carrier_name==DHL');

        /** @When the listing is read */
        $actual = $this->get(FindDispatches::class)->handle($request);
        $body = (array)json_decode($actual->getBody()->__toString(), true);

        /** @Then only the dispatches of that carrier are answered */
        self::assertSame([FindDispatchesTest::FIRST_ID], array_column($body['data'], 'id'));
        self::assertSame('DHL', $body['data'][0]['carrier_name']);
        self::assertSame(96.4, $body['data'][0]['cost']);
    }

    public function testSortsByCost(): void
    {
        /** @Given a request sorted by cost */
        $request = DispatchRequests::get(path: '/dispatches?sort=cost,id');

        /** @When the listing is read */
        $actual = $this->get(FindDispatches::class)->handle($request);
        $body = (array)json_decode($actual->getBody()->__toString(), true);

        /** @Then the cheapest dispatch comes first */
        self::assertSame([4.94, 12.3, 96.4], array_column($body['data'], 'cost'));
    }
}
