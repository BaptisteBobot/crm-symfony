<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\ActivityUser;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActivityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Création des utilisateurs
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword('azerty');
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $manager->persist($user);
            $users[] = $user;
        }

        // Création des activités
        for ($i = 0; $i < 3; $i++) {
            $activity = new Activity();
            $activity->setName($faker->sentence);
            $activity->setStartDate($faker->dateTimeBetween('now', '+1 month'));
            $activity->setEndDate($faker->dateTimeBetween('+1 month', '+2 months'));
            $activity->setLocation($faker->city);
            $manager->persist($activity);

            // Attribution aléatoire d'utilisateurs à chaque activité
            $activityUsers = $faker->randomElements($users, $faker->numberBetween(1, 5));
            foreach ($activityUsers as $user) {
                $activityUser = new ActivityUser();
                $activityUser->setUser($user);
                $activityUser->setActivity($activity);
                $manager->persist($activityUser);
            }
        }

        $manager->flush();
    }
}
