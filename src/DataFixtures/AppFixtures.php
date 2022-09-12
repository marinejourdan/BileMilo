<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->userPasswordHasher=$userPasswordHasher;
    }
        public function load(ObjectManager $manager): void
    {
        $phone_1= New Phone();
        $phone_1->setName('Redmi Note Pro');
        $phone_1->setDescription('Un excellent rapport qualité-prix,un grand écran à l image fluide');
        $phone_1->setMake('Xiaomi');
        $phone_1->setPhoto('url a mettre');
        $phone_1->setPrice(9050);

        $manager->persist($phone_1);

        $client_1= New Client();
        $client_1->setName('Marley');
        $client_1->setSurname('bob');
        $client_1->setEmail('bob.marley@gmail.com');
        $client_1->setNumberStreet(4);
        $client_1->setNameStreet('des tilleuls');
        $client_1->setTypeStreet('rue');
        $client_1->setPostalCode('16210');
        $client_1->setTown('st Quentin de Chalais');


        $user_1=New User();
        $user_1->setName('société alapointe');
        $user_1->setEmail('alapointe@gmail.com');
        $user_1->setPassword($this->userPasswordHasher->hashPassword($user_1, "password"));
        $manager->persist($user_1);
        $client_1->setUser($user_1);

        $manager->persist($client_1);
        $manager->flush();
    }
}
