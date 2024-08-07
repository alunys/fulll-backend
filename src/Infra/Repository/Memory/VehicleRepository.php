<?php

declare(strict_types=1);

namespace Fulll\Infra\Repository\Memory;

use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

final class VehicleRepository implements VehicleRepositoryInterface
{
    /** @var Vehicle[] */
    static private array $vehicles = [];

    public function getById(string $id): ?Vehicle
    {
        return self::$vehicles[$id] ?? null;
    }

    public function save(Vehicle $vehicle): void
    {
        self::$vehicles[$vehicle->id] = $vehicle;
    }
}
