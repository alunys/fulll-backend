<?php

declare(strict_types=1);

namespace Fulll\App\Query;

final class GetVehicle
{
    public function __construct(public string $plateNumber)
    {
    }
}
