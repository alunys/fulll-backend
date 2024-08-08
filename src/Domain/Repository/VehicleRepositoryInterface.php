<?php

declare(strict_types=1);

namespace Fulll\Domain\Repository;

use Fulll\Domain\Model\Vehicle;

interface VehicleRepositoryInterface
{
    public function getByPlateNumber(string $plateNumber): ?Vehicle;

    public function save(Vehicle $vehicle): void;
}
