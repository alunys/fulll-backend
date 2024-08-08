<?php

declare(strict_types=1);

namespace Fulll\Domain\ValueObject;

final class Location
{
    public function __construct(public float $lat, public float $long)
    {
    }

    public function equals(self $location): bool
    {
        return $this->lat === $location->lat && $this->long === $location->long;
    }
}
