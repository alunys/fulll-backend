<?php

declare(strict_types=1);

namespace Fulll\App\Query;

use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Repository\FleetRepositoryInterface;

final readonly class GetUserFleetHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {
    }

    public function handle(GetUserFleet $getUserFleet): ?Fleet
    {
        return $this->fleetRepository->getByUserId($getUserFleet->userId);
    }
}
