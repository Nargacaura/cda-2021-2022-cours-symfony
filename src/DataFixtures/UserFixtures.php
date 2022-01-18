<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Faker\Factory as Faker;

class UserFixtures extends Fixture
{
    /**
     * Permet d'encoder un mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;    
    }


    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();
        
        $lastname = $faker->lastName();
        $firstname = $faker->firstName();
        $pseudo = $this->createPseudo($lastname, $firstname);

        $user = new User();
        $user
            ->setEmail('admin@email.com')
            ->setFirstName($firstname)
            ->setLastName($lastname)
            ->setPseudonym($pseudo)
            ->setRoles(['ROLE_ADMIN']) 
            ->setPassword($this->encoder->encodePassword($user, 'admin'))
        ;
        $manager->persist($user);

        for ($i=0; $i < 100; $i++) { 
            $lastname = $faker->lastName();
            $firstname = $faker->firstName();
            $pseudo = $this->createPseudo($lastname, $firstname);            ;
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setFirstName($firstname)
                ->setLastName($lastname)
                ->setPseudonym($pseudo)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createPseudo(string ...$concat): string
    {
        $pseudo = '';
        foreach ($concat as $key => $value) {
            $pseudo .= substr($value, 0, 3);
        }
        $pseudo = strtolower($pseudo);
        return $pseudo;
    }
}