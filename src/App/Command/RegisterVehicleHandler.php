<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\Exception\FleetNotFoundException;
use Fulll\Domain\Exception\VehicleIsAlreadyRegisteredInFleetException;
use Fulll\Domain\Exception\VehicleNotFoundException;
use Fulll\Domain\Repository\FleetRepositoryInterface;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

final readonly class RegisterVehicleHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository, private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @throws VehicleNotFoundException
     * @throws FleetNotFoundException
     * @throws VehicleIsAlreadyRegisteredInFleetException
     */
    public function handle(RegisterVehicle $registerVehicle): void
    {
        if (null === ($vehicle = $this->vehicleRepository->getByPlateNumber($registerVehicle->vehiclePlateNumber))) {
            throw new VehicleNotFoundException("Vehicle with plate number '{$registerVehicle->vehiclePlateNumber}' not found");
        }

        if (null === ($fleet = $this->fleetRepository->getById($registerVehicle->fleetId))) {
            throw new FleetNotFoundException("Fleet with id '{$registerVehicle->fleetId}' not found");
        }

        $fleet->registerVehicle($vehicle);

        $this->fleetRepository->save($fleet);
    }
}
