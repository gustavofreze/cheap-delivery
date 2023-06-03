<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Actions\Shipping;

use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Name;
use CheapDelivery\Domain\Models\Person;
use CheapDelivery\Domain\Models\Product;
use CheapDelivery\Domain\Models\Shipments;
use CheapDelivery\Domain\Models\Weight;
use CheapDelivery\Domain\Ports\Outbound\Carriers;
use CheapDelivery\Driver\Http\Exceptions\NoEligibleCarriers;
use CheapDelivery\Driver\Http\HttpCode;
use CheapDelivery\Driver\Http\HttpResponse;
use CheapDelivery\Driver\Http\HttpResponseAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class CalculateShipping implements HttpResponse
{
    use HttpResponseAdapter;

    public function __construct(
        private readonly Carriers $carriers,
        private readonly CalculateShippingValidator $validator
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $body = $this->bodyFromRequest(request: $request);
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

            return $this
                ->withHttpCode(httpCode: HttpCode::OK)
                ->withPayload(payload: $shipment->toArray())
                ->reply(response: $response);
        } catch (Throwable $exception) {
            return $this
                ->withException(exception: $exception)
                ->reply(response: $response);
        }
    }
}
