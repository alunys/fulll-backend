<?php

declare(strict_types=1);

namespace Fulll\App\Query;

use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

final readonly class GetVehicleHandler
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function handle(GetVehicle $getVehicle): ?Vehicle
    {
        return $this->vehicleRepository->getByPlateNumber($getVehicle->plateNumber);
    }
}
