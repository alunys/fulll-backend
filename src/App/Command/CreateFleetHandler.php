<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\Event\FleetCreatedEvent;
use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Repository\FleetRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class CreateFleetHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function handle(CreateFleet $createFleet): void
    {
        $fleet = new Fleet($createFleet->userId);

        $this->fleetRepository->save($fleet);
        $this->eventDispatcher->dispatch(new FleetCreatedEvent($fleet->id));
    }
}
