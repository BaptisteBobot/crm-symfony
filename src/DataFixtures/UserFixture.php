<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // create user with email 'toto@toto.fr'
        $userToto = new User();
        $userToto->setEmail('toto@toto.fr');
        $userToto->setRoles(['ROLE_ADMIN']);
        $userToto->setFirstName($faker->firstName);
        $userToto->setLastName($faker->lastName);
        $userToto->setPhone($faker->phoneNumber);
        $userToto->setPassword($this->passwordHasher->hashPassword(
            $userToto,
            'azerty'
        ));
        $userToto->setIsVerified(true);
        $manager->persist($userToto);

        // create user with email 'tata@tata.fr'
        $userTata = new User();
        $userTata->setEmail('tata@tata.fr');
        $userTata->setRoles(['ROLE_USER']);
        $userTata->setFirstName($faker->firstName);
        $userTata->setLastName($faker->lastName);
        $userTata->setPhone($faker->phoneNumber);
        $userTata->setPassword($this->passwordHasher->hashPassword(
            $userTata,
            'azerty'
        ));
        $userTata->setIsVerified(true);
        $manager->persist($userTata);

        // create other users
        for ($i = 0; $i < 8; $i++) {
            $user = new User();
            $user->setEmail($faker->safeEmail);
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setPhone($faker->phoneNumber);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'azerty'
            ));
            $user->setIsVerified(true);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
