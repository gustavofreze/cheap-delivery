<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Database\Mongo;

use CheapDelivery\Driven\Database\DatabaseSettings;
use MongoDB\Client;

final class MongoAdapter
{
    private Client $client;

    private string $collectionName;

    public function __construct(private DatabaseSettings $settings)
    {
        $this->client = new Client(uri: $this->settings->getUri(), uriOptions: []);
        $this->client->selectDatabase($this->settings->getDatabaseName());
    }

    public function collection(string $collectionName): MongoAdapter
    {
        $this->collectionName = $collectionName;
        return $this;
    }

    public function find(array $filter = [], array $options = []): array
    {
        $collection = $this->client->selectCollection(
            databaseName: $this->settings->getDatabaseName(),
            collectionName: $this->collectionName
        );

        return $collection->find($filter, $options)->toArray();
    }
}
