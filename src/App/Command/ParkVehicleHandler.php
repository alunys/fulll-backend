<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\Exception\VehicleNotFoundException;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

final readonly class ParkVehicleHandler
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function handle(ParkVehicle $parkVehicle)
    {
        if (null === ($vehicle = $this->vehicleRepository->getById($parkVehicle->vehicleId))) {
            throw new VehicleNotFoundException("Vehicle with id '{$parkVehicle->vehicleId}' not found");
        }

        $vehicle->park($parkVehicle->location);

        $this->vehicleRepository->save($vehicle);
    }
}
