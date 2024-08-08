<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\Exception\VehicleNotFoundException;
use Fulll\Domain\Exception\VehiculeAlreadyParkedAtThisLocationException;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

final readonly class ParkVehicleHandler
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @throws VehiculeAlreadyParkedAtThisLocationException
     * @throws VehicleNotFoundException
     */
    public function handle(ParkVehicle $parkVehicle): void
    {
        if (null === ($vehicle = $this->vehicleRepository->getByPlateNumber($parkVehicle->plateNumber))) {
            throw new VehicleNotFoundException("Vehicle with id '{$parkVehicle->plateNumber}' not found");
        }

        $vehicle->park($parkVehicle->location);

        $this->vehicleRepository->save($vehicle);
    }
}
