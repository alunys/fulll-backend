<?php

declare(strict_types=1);

namespace Fulll\Domain\Repository;

use Fulll\Domain\Model\Vehicle;

interface VehicleRepositoryInterface
{
    public function getById(string $id): ?Vehicle;

    public function save(Vehicle $vehicle): void;
}
