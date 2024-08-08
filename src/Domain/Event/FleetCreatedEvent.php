<?php

namespace Fulll\Domain\Event;

use Fulll\Domain\Model\Fleet;

final readonly class FleetCreatedEvent
{
    public function __construct(
        public string $fleetId,
    ) {
    }
}
