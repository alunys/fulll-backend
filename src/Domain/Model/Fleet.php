<?php

declare(strict_types=1);

namespace Fulll\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Fulll\Domain\Exception\VehicleIsAlreadyRegisteredInFleetException;

class Fleet
{
    public readonly string $id;
    /** @var Collection<array-key, Vehicle> */
    private Collection $vehicles;

    public function __construct(
        public string $userId,
    ) {
        $this->id = uniqid();
        $this->vehicles = new ArrayCollection();
    }

    /**
     * @throws VehicleIsAlreadyRegisteredInFleetException
     */
    public function registerVehicle(Vehicle $vehicle): self
    {
        if ($this->hasVehicle($vehicle)) {
            throw new VehicleIsAlreadyRegisteredInFleetException('Vehicle is already part of the fleet');
        }

        $this->vehicles->add($vehicle);

        return $this;
    }

    public function hasVehicle(Vehicle $vehicle): bool
    {
        return null !== $this->findVehicle($vehicle->plateNumber);
    }

    public function findVehicle(string $plateNumber): ?Vehicle
    {
        foreach ($this->vehicles as $vehicle) {
            if ($vehicle->plateNumber === $plateNumber) {
                return $vehicle;
            }
        }

        return null;
    }
}
