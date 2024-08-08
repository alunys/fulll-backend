<?php

declare(strict_types=1);

namespace Fulll\Domain\Model;

use Fulll\Domain\Exception\VehiculeAlreadyParkedAtThisLocationException;
use Fulll\Domain\ValueObject\Location;

class Vehicle
{
    public readonly string $id;

    public function __construct(
        public readonly string $plateNumber,
        private ?Location $location = null,
    )
    {
        $this->id = uniqid();
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @throws VehiculeAlreadyParkedAtThisLocationException
     */
    public function park(Location $location): self
    {
        if (null !== $this->location && $this->location->equals($location)) {
            throw new VehiculeAlreadyParkedAtThisLocationException();
        }

        $this->location = $location;

        return $this;
    }
}
