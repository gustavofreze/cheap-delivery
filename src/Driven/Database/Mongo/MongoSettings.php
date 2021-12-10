<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Database\Mongo;

use CheapDelivery\Driven\Database\DatabaseSettings;

final class MongoSettings implements DatabaseSettings
{
    private string $uri;

    private string $user;

    private string $password;

    private string $databaseName;

    public function __construct()
    {
        $this->user = getenv('MONGO_DATABASE_USER');
        $this->password = getenv('MONGO_DATABASE_PASSWORD');
        $this->databaseName = getenv('MONGO_DATABASE_NAME');
        $this->uri = sprintf(
            'mongodb://%s:%s@%s:%d/%s',
            $this->user,
            $this->password,
            getenv('MONGO_DATABASE_HOST'),
            getenv('MONGO_DATABASE_PORT'),
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
