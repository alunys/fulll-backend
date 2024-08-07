<?php

declare(strict_types=1);

namespace Fulll\Domain\Model;

use Fulll\Domain\Exception\VehicleIsAlreadyRegisteredInFleetException;

class Fleet
{
    public readonly string $id;

    /**
     * @param Vehicle[] $vehicles
     */
    public function __construct(
        public string $userId,
        public array $vehicles= [],
    )
    {
        $this->id = uniqid();
    }

    /**
     * @throws VehicleIsAlreadyRegisteredInFleetException
     */
    public function registerVehicle(Vehicle $vehicle): self
    {
        if ($this->hasVehicle($vehicle)) {
            throw new VehicleIsAlreadyRegisteredInFleetException('Vehicle is already part of the fleet');
        }

        $this->vehicles[] = $vehicle;

        return $this;
    }

    public function hasVehicle(Vehicle $vehicle): bool
    {
        foreach ($this->vehicles as $vehicleFleet) {
            if ($vehicleFleet->id === $vehicle->id) {
                return true;
            }
        }

        return false;
    }
}
