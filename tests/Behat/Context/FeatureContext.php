<?php

declare(strict_types=1);

namespace Fulll\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;
use Exception;
use Fulll\App\Command\ParkVehicle;
use Fulll\App\Command\ParkVehicleHandler;
use Fulll\App\Command\RegisterVehicle;
use Fulll\App\Command\RegisterVehicleHandler;
use Fulll\App\Query\GetUserFleet;
use Fulll\App\Query\GetUserFleetHandler;
use Fulll\App\Query\GetVehicle;
use Fulll\App\Query\GetVehicleHandler;
use Fulll\Domain\Exception\VehicleIsAlreadyRegisteredInFleetException;
use Fulll\Domain\Exception\VehiculeAlreadyParkedAtThisLocationException;
use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\ValueObject\Location;
use Fulll\Infra\Repository\Memory\FleetRepository;
use Fulll\Infra\Repository\Memory\VehicleRepository;
use ReflectionProperty;

class FeatureContext implements Context
{
    private const string MY_USER_ID = 'my_user_id';
    private const string OTHER_USER_ID = 'other_user_id';

    private ParkVehicleHandler $parkVehicleHandler;
    private RegisterVehicleHandler $registerVehicleHandler;
    private GetVehicleHandler $getVehicleHandler;
    private GetUserFleetHandler $getUserFleetHandler;
    private FleetRepository $fleetRepository;
    private VehicleRepository $vehiculeRepository;
    private ?Fleet $myFleet = null;
    private ?Fleet $anotherUserFleet = null;
    private ?Vehicle $aVehicle = null;
    private ?Exception $exceptionCaught = null;
    private Location $aLocation;

    public function __construct()
    {
        $this->vehiculeRepository = new VehicleRepository();
        $this->fleetRepository = new FleetRepository();
        $this->parkVehicleHandler = new ParkVehicleHandler($this->vehiculeRepository );
        $this->registerVehicleHandler = new RegisterVehicleHandler($this->fleetRepository, $this->vehiculeRepository );
        $this->getVehicleHandler = new GetVehicleHandler($this->vehiculeRepository );
        $this->getUserFleetHandler = new GetUserFleetHandler($this->fleetRepository);
    }

    #[BeforeScenario]
    public function loadFixtures(): void
    {
        // Reset fleets stored in memory
        $fleets = new ReflectionProperty($this->fleetRepository, 'fleets');
        $fleets->setValue($this->fleetRepository, []);

        // Reset vehicles stored in memory
        $vehicles = new ReflectionProperty($this->vehiculeRepository, 'vehicles');
        $vehicles->setValue($this->vehiculeRepository, []);

        $myFleet = new Fleet(self::MY_USER_ID);
        $this->fleetRepository->save($myFleet);

        $otherUserFleet = new Fleet(self::OTHER_USER_ID);
        $this->fleetRepository->save($otherUserFleet);

        for ($i = 0; $i < 10; $i++) {
            $this->vehiculeRepository->save(new Vehicle());
        }
    }

    #[Given('my fleet')]
    public function myFleet(): void
    {
        $this->myFleet = $this->getUserFleetHandler->handle(new GetUserFleet(self::MY_USER_ID));
    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {
        $this->anotherUserFleet = $this->getUserFleetHandler->handle(new GetUserFleet(self::OTHER_USER_ID));
    }

    #[Given('a vehicle')]
    public function aVehicle(): void
    {
        $this->aVehicle = new Vehicle();
        $this->vehiculeRepository->save($this->aVehicle);
    }

    #[Given('a location')]
    public function aLocation(): void
    {
        $this->aLocation = new Location(1.23, 4.56);
    }

    #[Given('this vehicle has been registered into the other user\'s fleet')]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUserFleet(): void
    {
        $this->registerVehicleHandler->handle(new RegisterVehicle($this->aVehicle->id, $this->anotherUserFleet->id));
    }

    #[When('I register this vehicle into my fleet')]
    #[Given('I have registered this vehicle into my fleet')]
    #[Given('I try to register this vehicle into my fleet')]
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        try {
            $this->registerVehicleHandler->handle(new RegisterVehicle($this->aVehicle->id, $this->myFleet->id));
        } catch (\Throwable $e) {
            $this->exceptionCaught = $e;
        }
    }

    #[When('I park my vehicle at this location')]
    public function iParkThisVehicleAtThisLocation(): void
    {
        $this->parkVehicleHandler->handle(new ParkVehicle($this->aVehicle->id, $this->aLocation));
    }

    #[Then('this vehicle should be part of my vehicle fleet')]
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        if (!$this->getUserFleetHandler->handle(new GetUserFleet(self::MY_USER_ID))->hasVehicle($this->aVehicle)) {
            throw new \RuntimeException('Vehicle is not part of the fleet');
        }
    }

    #[When('my vehicle has been parked into this location')]
    #[When('I try to park my vehicle at this location')]
    public function myVehicleHasBeenParkedIntoThisLocation(): void
    {
        try {
            $this->parkVehicleHandler->handle(new ParkVehicle($this->aVehicle->id, $this->aLocation));
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
        $vehicle = $this->getVehicleHandler->handle(new GetVehicle($this->aVehicle->id));
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
}
