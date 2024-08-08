<?php

declare(strict_types=1);

namespace Fulll\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fulll\Domain\Model\Fleet;

class FleetFixtures extends Fixture
{
    public const string MY_USER_ID = 'my_user_id';
    public const string OTHER_USER_ID = 'other_user_id';

    public function load(ObjectManager $manager): void
    {
        $myFleet = new Fleet(self::MY_USER_ID);
        $otherUserFleet = new Fleet(self::OTHER_USER_ID);

        $manager->persist($myFleet);
        $manager->persist($otherUserFleet);
        $manager->flush();
    }
}
