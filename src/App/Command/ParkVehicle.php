<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\ValueObject\Location;

final readonly class ParkVehicle
{
    public function __construct(
        public string   $plateNumber,
        public Location $location,
    )
    {
    }
}
