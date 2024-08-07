<?php

declare(strict_types=1);

namespace Fulll\App\Query;

final readonly class GetUserFleet
{
    public function __construct(public string $userId)
    {
    }
}
