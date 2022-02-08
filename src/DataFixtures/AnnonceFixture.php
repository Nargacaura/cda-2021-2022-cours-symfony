<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use App\Entity\Annonce;
use App\Entity\Tag;
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
        $faker = Faker::create('fr_FR');
        $users = $this->userRepository->findAll();
        $usersLength = count($users)-1;

        $tagRepository = $manager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();
        $tagLength = count($tags)-1;

        for ($i=0; $i < 500; $i++) { 
            $randomKey = rand(0, $usersLength);
            $user = $users[$randomKey];
            $tag = $tags[rand(0, $tagLength)];
            
            $annonce = new Annonce();
            $annonce
                ->setTitle($faker->words($faker->numberBetween(1, 10), true))
                ->setDescription($faker->sentences($faker->numberBetween(1, 5), true))
                ->setPrice($faker->numberBetween(1, 999999999))
                ->setStatus($faker->numberBetween(0, 4))
                ->setSold($faker->boolean())
                ->setUser($user)
                ->addTag($tag)
            ;
            $manager->persist($annonce);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TagFixtures::class
        ];
    }
}
