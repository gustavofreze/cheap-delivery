<?php

namespace CheapDelivery\Driven\Carrier\Repository;

final class Queries
{
    public const FIND_ALL = 'SELECT BIN_TO_UUID(id) AS id, name, cost_modality AS costModality FROM carrier;';
}
