<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use App\Entity\House;
use App\Entity\User;
use App\Entity\Sponsor;

class DataFixtures extends Fixture
{
    private $faker;

    public function load(ObjectManager $manager)
    {

        // initialise faker instance/object
        $this->faker = Factory::create();

        // Create admin user
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setPassword('admin');
        // Commiting the object to database
        $manager->persist($adminUser);
        $manager->flush();

        // Create staff user
        $staffUser = new User();
        $staffUser->setUsername('staff');
        $staffUser->setPassword('staff');
        // Commiting the object to database
        $manager->persist($staffUser);
        $manager->flush();

        // Random number of additional users, between 5 and 10
        $maxNumberOfUsers = rand(5, 10);
        for ($x = 0; $x < $maxNumberOfUsers; $x++) {
            $randomUser = new User();
            // Generate random username
            // and use that same value for the username and password
            $randomUsername = $this->faker->userName;
            $randomUser->setUsername($randomUsername);
            $randomUser->setPassword($randomUsername);
            // Commiting the object to database
            $manager->persist($randomUser);
            $manager->flush();
        }



    }
}