<?php

declare(strict_types=1);

namespace Fulll\Ui\Console;

use Fulll\App\Command\CreateFleet;
use Fulll\App\Command\CreateFleetHandler;
use Fulll\Domain\Event\FleetCreatedEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsCommand(name: 'fulll:fleet-create', description: 'Create a new fleet for a user')]
final class FleetCreateCommand extends Command
{
    public function __construct(private readonly CreateFleetHandler $createFleetHandler, private readonly EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::REQUIRED, 'The id of the user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('userId');

        /** @psalm-suppress TypeDoesNotContainType */
        if (!\is_string($userId)) {
            throw new \InvalidArgumentException('Invalid argument type');
        }

        $this->eventDispatcher->addListener(FleetCreatedEvent::class, static function (FleetCreatedEvent $fleetCreatedEvent) use ($output): void {
            $output->writeln("Fleet created with id '{$fleetCreatedEvent->fleetId}'");
        });

        $this->createFleetHandler->handle(new CreateFleet($userId));

        return self::SUCCESS;
    }
}
