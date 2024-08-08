<?php

declare(strict_types=1);

namespace Fulll\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\ValueObject\Location;

class VehicleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $manager->persist(new Vehicle("AB-123-45-$i", new Location((float) sprintf('%d.%d', rand(-90,90) ,$i), (float) sprintf('%d.%d', rand(-180,180) ,$i))));
        }

        $manager->flush();
    }
}
