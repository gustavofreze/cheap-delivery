<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Modalities\CostModality;

interface CostModalityFactory
{
    public const FIXED = 'Fixed';
    public const LINEAR = 'Linear';
    public const PARTIAL = 'Partial';
    public const COMPOSITE = 'Composite';

    public function build(): CostModality;
}
