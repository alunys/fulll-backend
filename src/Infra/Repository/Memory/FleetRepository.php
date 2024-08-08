<?php

declare(strict_types=1);

namespace Fulll\Infra\Repository\Memory;

use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Repository\FleetRepositoryInterface;

final class FleetRepository implements FleetRepositoryInterface
{
    /** @var Fleet[] */
    private static array $fleets = [];

    public function getById(string $id): ?Fleet
    {
        return self::$fleets[$id] ?? null;
    }

    public function getByUserId(string $userId): ?Fleet
    {
        foreach (self::$fleets as $fleet) {
            if ($fleet->userId === $userId) {
                return $fleet;
            }
        }

        return null;
    }

    public function save(Fleet $fleet): void
    {
        // Check if another fleet is already assigned to the user of this fleet
        foreach (self::$fleets as $fleetId => $existingFleet) {
            if ($fleetId === $fleet->id) {
                continue;
            }

            if ($existingFleet->userId === $fleet->userId) {
                throw new \RuntimeException('Fleet already exists for this user');
            }
        }

        self::$fleets[$fleet->id] = $fleet;
    }
}
