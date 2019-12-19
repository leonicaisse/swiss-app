<?php

namespace App\DataFixtures;

use App\Entity\Command;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CommandFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; $i++) {
            $faker = Factory::create('fr_FR');
            $command = new Command();
            $command
                ->setReference("201900." . substr("0000{$i}", -4))
                ->setProduct("MOD" . substr("0000{$i}", -4))
                ->setQuantity(($faker->numberBetween(3, 80)) * 100)
                ->setState($faker->numberBetween(0, count(Command::STATE)-1));
            $manager->persist($command);
        }
        $manager->flush();
    }
}
