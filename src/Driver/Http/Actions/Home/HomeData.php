<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Actions\Home;

final class HomeData
{
    public function __construct(private string $name, private string $developedBy)
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'developed_by' => $this->developedBy
        ];
    }
}
