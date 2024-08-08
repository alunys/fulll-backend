<?php

declare(strict_types=1);

namespace Fulll\Domain\Event;

final readonly class FleetCreatedEvent
{
    public function __construct(
        public string $fleetId,
    ) {
    }
}
