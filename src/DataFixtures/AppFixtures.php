<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Phone;
use App\Entity\User;
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

        $user_1= New User();
        $user_1->setName('Marley');
        $user_1->setSurname('bob');
        $user_1->setMail('bob.marley@gmail.com');
        $user_1->setPassword('proutprout');
        $user_1->setNumberStreet(4);
        $user_1->setNameStreet('des tilleuls');
        $user_1->setTypeStreet('rue');
        $user_1->setPostalCode('16210');
        $user_1->setTown('st Quenti de Chalais');

        $client_1=New Client();
        $client_1->setName('société alapointe');
        $manager->persist($client_1);
        $user_1->setClient($client_1);




        $manager->persist($user_1);
        $manager->flush();
    }
}
