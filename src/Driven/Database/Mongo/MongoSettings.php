<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Database\Mongo;

use CheapDelivery\Driven\Database\DatabaseSettings;
use CheapDelivery\Driven\Environment\Environment;

final class MongoSettings implements DatabaseSettings
{
    private string $uri;

    private string $user;

    private string $password;

    private string $databaseName;

    public function __construct(private Environment $environment)
    {
        $this->user = $this->environment->get('MONGO_DATABASE_USER');
        $this->password = $this->environment->get('MONGO_DATABASE_PASSWORD');
        $this->databaseName = $this->environment->get('MONGO_DATABASE_NAME');
        $this->uri = sprintf(
            'mongodb://%s:%s@%s:%d/%s',
            $this->user,
            $this->password,
            $this->environment->get('MONGO_DATABASE_HOST'),
            $this->environment->get('MONGO_DATABASE_PORT'),
            $this->databaseName
        );
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }
}
