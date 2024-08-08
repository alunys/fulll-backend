<?php

declare(strict_types=1);

namespace Fulll\Ui\Console;

use Fulll\App\Command\RegisterVehicle;
use Fulll\App\Command\RegisterVehicleHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fulll:fleet-register-vehicle', description: 'Register a vehicle to a fleet')]
final class FleetRegisterVehicleCommand extends Command
{
    public function __construct(private readonly RegisterVehicleHandler $registerVehicleHandler)
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

        if (!\is_string($fleetId) || !\is_string($vehiclePlateNumber)) {
            throw new \InvalidArgumentException('Invalid argument type');
        }

        $this->registerVehicleHandler->handle(new RegisterVehicle($vehiclePlateNumber, $fleetId));

        return self::SUCCESS;
    }
}
