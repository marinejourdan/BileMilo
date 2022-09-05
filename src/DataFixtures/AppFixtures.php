<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $phone_1= New Phone();
        $phone_1->setName('Redmi Note Pro');
        $phone_1->setDescription('Un excellent rapport qualité-prix,un grand écran à l image fluide');
        $phone_1->setMake('Xiaomi');
        $phone_1->setPhoto('url a mettre');
        $phone_1->setPrice(90.50);

        $manager->persist($phone_1);
        $manager->flush();
    }
}
