<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Annonce;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixture extends Fixture implements DependentFixtureInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $this->userRepository->findAll();
        $usersLength = count($users)-1;
        for ($i=0; $i < 1000; $i++) { 
            // permet d'avoir un utilisateur random
            // possible à faire avec Faker mais plus lourd en ressource
            $randomKey = rand(0, $usersLength);
            $user = $users[$randomKey];

            $annonce = new Annonce();
            $annonce
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setPrice($faker->numberBetween(10, 100))
                ->setStatus($faker->numberBetween(0, 4))
                ->setSold(false)
                ->setUser($user)
            ;
            $manager->persist($annonce);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
