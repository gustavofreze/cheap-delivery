<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Actions\Shipping;

use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Person;
use CheapDelivery\Core\Models\Product;
use CheapDelivery\Core\Models\Shipments;
use CheapDelivery\Core\Models\Weight;
use CheapDelivery\Core\Repository\Carriers;
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

    public function __construct(private Carriers $carriers, private CalculateShippingValidator $validator)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $body = $this->bodyFromRequest($request);
            $this->validator->validate($body);

            $person = new Person(
                name: new Name($body['person']['name']),
                distance: new Distance($body['person']['distance'])
            );

            $product = new Product(
                name: new Name($body['product']['name']),
                weight: new Weight($body['product']['weight'])
            );

            $carriers = $this->carriers->findAll();
            $shipments = Shipments::from($carriers, $product->getWeight(), $person->getDistance());
            $shipment = $shipments->lowestCost();

            if (is_null($shipment)) {
                throw new NoEligibleCarriers();
            }

            return $this
                ->withHttpCode(HttpCode::OK)
                ->withPayload($shipment->toArray())
                ->reply($response);
        } catch (Throwable $exception) {
            return $this
                ->withException($exception)
                ->reply($response);
        }
    }
}
