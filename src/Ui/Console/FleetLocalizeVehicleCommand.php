<?php

declare(strict_types=1);

namespace Fulll\Ui\Console;

use Fulll\App\Query\GetFleet;
use Fulll\App\Query\GetFleetHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fulll:fleet-localize-vehicle', description: 'Localize a vehicle that is part of a fleet')]
final class FleetLocalizeVehicleCommand extends Command
{
    public function __construct(private readonly GetFleetHandler $getFleetHandler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fleetId', InputArgument::REQUIRED, 'The id of the fleet.')
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'The vehicle plate number.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fleetId = $input->getArgument('fleetId');
        $vehiclePlateNumber = $input->getArgument('vehiclePlateNumber');

        /** @psalm-suppress TypeDoesNotContainType */
        if (!\is_string($fleetId) || !\is_string($vehiclePlateNumber)) {
            throw new \InvalidArgumentException('Invalid argument type');
        }

        if (null === ($fleet = $this->getFleetHandler->handle(new GetFleet($fleetId)))) {
            $output->writeln('Fleet not found');

            return self::FAILURE;
        }

        if (null === ($vehicle = $fleet->findVehicle($vehiclePlateNumber))) {
            $output->writeln('Vehicle not found in fleet');

            return self::FAILURE;
        }

        $location = $vehicle->getLocation();
        if (null === $location) {
            $output->writeln('Vehicle has no location');
        } else {
            $output->writeln("Vehicle localized at lat : {$location->lat}, long: {$location->long}");
        }

        return self::SUCCESS;
    }
}
