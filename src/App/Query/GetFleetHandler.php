<?php

declare(strict_types=1);

namespace Fulll\App\Query;

use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Repository\FleetRepositoryInterface;

final readonly class GetFleetHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {
    }

    public function handle(GetFleet $getFleet): ?Fleet
    {
        return $this->fleetRepository->getById($getFleet->id);
    }
}
