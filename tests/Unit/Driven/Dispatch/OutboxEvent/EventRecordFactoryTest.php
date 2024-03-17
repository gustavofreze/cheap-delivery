<?php

namespace CheapDelivery\Driven\Dispatch\OutboxEvent;

use CheapDelivery\Driven\Dispatch\OutboxEvent\Mocks\RejectedDispatch;
use LogicException;
use PHPUnit\Framework\TestCase;

class EventRecordFactoryTest extends TestCase
{
    public function testExceptionWhenUnsupportedEventType(): void
    {
        /** @Given I have an unmapped event */
        $event = new RejectedDispatch();

        /** @Then an exception indicating unsupported event type should be thrown */
        $template = 'Event <%s> is not supported. Unable to create an EventRecord.';
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf($template, $event::class));

        /** @When I try to build an event record with this event */
        EventRecordFactory::from(event: $event);
    }
}
