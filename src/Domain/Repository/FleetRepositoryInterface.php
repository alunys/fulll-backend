<?php

declare(strict_types=1);

namespace Fulll\Domain\Repository;

use Fulll\Domain\Model\Fleet;

interface FleetRepositoryInterface
{
    public function getById(string $id): ?Fleet;

    public function getByUserId(string $userId): ?Fleet;

    public function save(Fleet $fleet): void;
}
