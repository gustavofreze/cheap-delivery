<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Database;

interface DatabaseSettings
{
    public function getUri(): string;

    public function getUser(): string;

    public function getPassword(): string;

    public function getDatabaseName(): string;
}
