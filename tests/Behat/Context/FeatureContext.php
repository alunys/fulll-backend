<?php

declare(strict_types=1);

namespace Fulll\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Fulll\App\Command\CreateFleet;
use Fulll\App\Command\CreateFleetHandler;
use Fulll\App\Command\ParkVehicle;
use Fulll\App\Command\ParkVehicleHandler;
use Fulll\App\Command\RegisterVehicle;
use Fulll\App\Command\RegisterVehicleHandler;
use Fulll\App\Query\GetUserFleet;
use Fulll\App\Query\GetUserFleetHandler;
use Fulll\App\Query\GetVehicle;
use Fulll\App\Query\GetVehicleHandler;
use Fulll\DataFixtures\FleetFixtures;
use Fulll\Domain\Exception\VehicleIsAlreadyRegisteredInFleetException;
use Fulll\Domain\Exception\VehiculeAlreadyParkedAtThisLocationException;
use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\Repository\VehicleRepositoryInterface;
use Fulll\Domain\ValueObject\Location;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @internal
 *
 * @coversNothing
 */
final class FeatureContext extends KernelTestCase implements Context
{
    private ?Fleet $myFleet = null;
    private ?Fleet $anotherUserFleet = null;
    private ?Vehicle $aVehicle = null;
    private ?\Exception $exceptionCaught = null;
    private Location $aLocation;
    private string $aUserId;
    private Container $container;

    public function __construct()
    {
        self::bootKernel();
        $this->container = self::getContainer();
    }

    #[Given('my fleet')]
    public function myFleet(): void
    {
        $this->myFleet = $this->container->get(GetUserFleetHandler::class)->handle(new GetUserFleet(FleetFixtures::MY_USER_ID));
    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {
        $this->anotherUserFleet = $this->container->get(GetUserFleetHandler::class)->handle(new GetUserFleet(FleetFixtures::OTHER_USER_ID));
    }

    #[Given('a vehicle')]
    public function aVehicle(): void
    {
        $this->aVehicle = new Vehicle('AB-321-45-6');
        $this->container->get(VehicleRepositoryInterface::class)->save($this->aVehicle);
    }

    #[Given('a location')]
    public function aLocation(): void
    {
        $this->aLocation = new Location(1.23, 4.56);
    }

    #[Given('this vehicle has been registered into the other user\'s fleet')]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUserFleet(): void
    {
        $this->container->get(RegisterVehicleHandler::class)->handle(new RegisterVehicle($this->aVehicle->plateNumber, $this->anotherUserFleet->id));
    }

    #[When('I register this vehicle into my fleet')]
    #[Given('I have registered this vehicle into my fleet')]
    #[Given('I try to register this vehicle into my fleet')]
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        try {
            $this->container->get(RegisterVehicleHandler::class)->handle(new RegisterVehicle($this->aVehicle->plateNumber, $this->myFleet->id));
        } catch (\Throwable $e) {
            $this->exceptionCaught = $e;
        }
    }

    #[When('I park my vehicle at this location')]
    public function iParkThisVehicleAtThisLocation(): void
    {
        $this->container->get(ParkVehicleHandler::class)->handle(new ParkVehicle($this->aVehicle->plateNumber, $this->aLocation));
    }

    #[Then('this vehicle should be part of my vehicle fleet')]
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        if (!$this->container->get(GetUserFleetHandler::class)->handle(new GetUserFleet(FleetFixtures::MY_USER_ID))->hasVehicle($this->aVehicle)) {
            throw new \RuntimeException('Vehicle is not part of the fleet');
        }
    }

    #[When('my vehicle has been parked into this location')]
    #[When('I try to park my vehicle at this location')]
    public function myVehicleHasBeenParkedIntoThisLocation(): void
    {
        try {
            $this->container->get(ParkVehicleHandler::class)->handle(new ParkVehicle($this->aVehicle->plateNumber, $this->aLocation));
        } catch (\Throwable $e) {
            $this->exceptionCaught = $e;
        }
    }

    #[Then('I should be informed this this vehicle has already been registered into my fleet')]
    public function iShouldBeInformedThisThisVehicleHasBeenAlreadyRegisteredIntoMyFleet(): void
    {
        if (!$this->exceptionCaught instanceof VehicleIsAlreadyRegisteredInFleetException) {
            throw new \RuntimeException('I have not been informed that the vehicle has already been registered into the fleet');
        }
    }

    #[Then('the known location of my vehicle should verify this location')]
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        $vehicle = $this->container->get(GetVehicleHandler::class)->handle(new GetVehicle($this->aVehicle->plateNumber));
        if (!$vehicle->getLocation()->equals($this->aLocation)) {
            throw new \RuntimeException('Vehicle location is not the expected one');
        }
    }

    #[Then('I should be informed that my vehicle is already parked at this location')]
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        if (!$this->exceptionCaught instanceof VehiculeAlreadyParkedAtThisLocationException) {
            throw new \RuntimeException('I have not been informed that the vehicle is already parked at this location');
        }
    }

    #[Given('A user with id :id')]
    public function aUserWithId(string $id): void
    {
        $this->aUserId = $id;
    }

    #[When('I create a fleet for this user')]
    public function iCreateAFleetForThisUser(): void
    {
        $this->container->get(CreateFleetHandler::class)->handle(new CreateFleet($this->aUserId));
    }

    #[Then('I should be able to retrieve the fleet created for user')]
    public function iShouldBeAbleToRetrieveTheFleetCreatedForUser(): void
    {
        self::assertInstanceOf(Fleet::class, $this->container->get(GetUserFleetHandler::class)->handle(new GetUserFleet($this->aUserId)));
    }
}
