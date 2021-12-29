<?php

namespace CheapDelivery\Driven\Environment;

interface Environment
{
    public function get(string $variable): string;
}
