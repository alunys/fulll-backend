<?php

declare(strict_types=1);

namespace Fulll\Infra\Repository\Memory;

use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

final class VehicleRepository implements VehicleRepositoryInterface
{
    /** @var Vehicle[] */
    private static array $vehicles = [];

    public function getByPlateNumber(string $plateNumber): ?Vehicle
    {
        return self::$vehicles[$plateNumber] ?? null;
    }

    public function save(Vehicle $vehicle): void
    {
        self::$vehicles[$vehicle->plateNumber] = $vehicle;
    }
}
