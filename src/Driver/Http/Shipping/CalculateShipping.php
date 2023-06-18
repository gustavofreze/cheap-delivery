<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Shipping;

use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Name;
use CheapDelivery\Domain\Models\Person;
use CheapDelivery\Domain\Models\Product;
use CheapDelivery\Domain\Models\Shipments;
use CheapDelivery\Domain\Models\Weight;
use CheapDelivery\Domain\Ports\Outbound\Carriers;
use CheapDelivery\Driver\Http\Shared\HttpResponseAdapter;
use CheapDelivery\Driver\Http\Shipping\Exceptions\NoEligibleCarriers;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TinyBlocks\Http\HttpResponse;

final class CalculateShipping extends HttpResponseAdapter
{
    public function __construct(
        private readonly Carriers $carriers,
        private readonly CalculateShippingValidator $validator
    ) {
    }

    protected function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $this->requestWithParsedBody();
        $this->validator->validate(request: $body);

        $person = new Person(
            name: new Name(value: $body['person']['name']),
            distance: new Distance(value: $body['person']['distance'])
        );

        $product = new Product(
            name: new Name(value: $body['product']['name']),
            weight: new Weight(value: $body['product']['weight'])
        );
        $carriers = $this->carriers->findAll();

        $shipments = Shipments::from(
            carriers: $carriers,
            weight: $product->getWeight(),
            distance: $person->getDistance()
        );
        $shipment = $shipments->lowestCost();

        if (is_null($shipment)) {
            throw new NoEligibleCarriers();
        }

        return HttpResponse::ok(data: $shipment->toArray());
    }
}
