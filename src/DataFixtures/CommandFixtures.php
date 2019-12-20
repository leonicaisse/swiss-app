<?php

namespace App\DataFixtures;

use App\Entity\Command;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CommandFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; $i++) {
            $faker = Factory::create('fr_FR');
            $product = new Product();
            $product->setQuantity(0)
                ->setReference("MOD" . substr("0000{$i}", -4));
            $manager->persist($product);
            $command = new Command();
            $command
                ->addProduct($product)
                ->setReference("201900." . substr("0000{$i}", -4))
                ->setQuantity(($faker->numberBetween(3, 80)) * 100)
                ->setState($faker->numberBetween(0, count(Command::STATE) - 1));
            $manager->persist($command);
        }
        $manager->flush();
    }
}
