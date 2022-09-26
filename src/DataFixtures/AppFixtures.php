<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

        public function load(ObjectManager $manager): void
        {
            $phone_1 = new Phone();
            $phone_1->setName('Redmi Note Pro');
            $phone_1->setDescription('Un excellent rapport qualité-prix,un grand écran à l image fluide');
            $phone_1->setMake('Xiaomi');
            $phone_1->setPhoto('url a mettre');
            $phone_1->setPrice(9050);

            $phone_2 = new Phone();
            $phone_2->setName('iPhone XR ');
            $phone_2->setDescription('Meilleur rapport qualité-prix. Les scores de durabilité de ce produit sont dans la moyenne de Back Market. Le reconditionneur respecte tous les points de notre charte qualité. ');
            $phone_2->setMake('Apple');
            $phone_2->setPhoto('url a mettre');
            $phone_2->setPrice(20050);

            $phone_3 = new Phone();
            $phone_3->setName('Reno8 Pro 5G ');
            $phone_3->setDescription('Terminal Bloc alimentation 80W Câble USB-C Adaptateur USB-A vers USB-C Carte de garantie Guide de démarrage et de sécurité ');
            $phone_3->setMake('Oppo');
            $phone_3->setPhoto('url a mettre');
            $phone_3->setPrice(10050);

            $phone_4 = new Phone();
            $phone_4->setName('Galaxy S 22 ');
            $phone_4->setDescription('Un écran magnifique, des performances solides et une grande autonomie de batterie ');
            $phone_4->setMake('Samsung');
            $phone_4->setPhoto('url a mettre');
            $phone_4->setPrice(1050);

            $phone_5 = new Phone();
            $phone_5->setName('Snapdragon 70 ');
            $phone_5->setDescription('Jaime beaucoup le design et les cameras du Honor J ai hâte de le recevoir bientôt !');
            $phone_5->setMake('Honor');
            $phone_5->setPhoto('url a mettre');
            $phone_5->setPrice(1050);

            $manager->persist($phone_5);
            $manager->persist($phone_4);
            $manager->persist($phone_3);
            $manager->persist($phone_2);
            $manager->persist($phone_1);

            $client_1 = new Client();
            $client_1->setName('Marley');
            $client_1->setSurname('bob');
            $client_1->setEmail('bob.marley@gmail.com');
            $client_1->setNumberStreet(4);
            $client_1->setNameStreet('des tilleuls');
            $client_1->setTypeStreet('rue');
            $client_1->setPostalCode('16210');
            $client_1->setTown('st Quentin de Chalais');

            $user_1 = new User();
            $user_1->setName('société alapointe');
            $user_1->setEmail('alapointe@gmail.com');
            $user_1->setPassword($this->userPasswordHasher->hashPassword($user_1, 'password'));

            $user_2 = new User();
            $user_2->setName('société hasbeen');
            $user_2->setEmail('hasbeen@gmail.com');
            $user_2->setPassword($this->userPasswordHasher->hashPassword($user_2, 'password'));

            $user_3 = new User();
            $user_3->setName('société toutcomptefait');
            $user_3->setEmail('toutcomptefait@gmail.com');
            $user_3->setPassword($this->userPasswordHasher->hashPassword($user_3, 'password'));

            $manager->persist($user_3);
            $manager->persist($user_2);
            $manager->persist($user_1);

            $client_1 = new Client();
            $client_1->setName('Marley');
            $client_1->setSurname('bob');
            $client_1->setEmail('bob@gmail.com');
            $client_1->setPostalCode('16210');
            $client_1->setTown('st Quentin de Chalais');

            $client_2 = new Client();
            $client_2->setName('Beyonce');
            $client_2->setSurname('Knowles');
            $client_2->setEmail('beyonce@gmail.com');
            $client_2->setNumberStreet(9);
            $client_2->setNameStreet('de la Grande Vallée');
            $client_2->setTypeStreet('avenue');
            $client_2->setPostalCode('14250');
            $client_2->setTown('Saint Antonin Noble Val');

            $client_3 = new Client();
            $client_3->setName('Shakira');
            $client_3->setSurname('chacha');
            $client_3->setEmail('chacha@gmail.com');
            $client_3->setNumberStreet(45);
            $client_3->setNameStreet('de la fontaine');
            $client_3->setTypeStreet('impasse');
            $client_3->setPostalCode('33300');
            $client_3->setTown('Merignac');

            $client_1->setUser($user_1);
            $client_2->setUser($user_3);
            $client_3->setUser($user_3);

            $manager->persist($client_1);
            $manager->persist($client_2);
            $manager->persist($client_3);
            $manager->flush();
        }
}
