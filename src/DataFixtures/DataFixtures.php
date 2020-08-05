<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use App\Entity\House;
use App\Entity\User;
use App\Entity\Sponsor;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataFixtures extends Fixture
{
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        // initialise faker instance/object
        $this->faker = Factory::create();

        // Create admin user
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setPassword($this->encoder->encodePassword($adminUser, 'admin'));
        // Commiting the object to database
        $manager->persist($adminUser);
        $manager->flush();

        // Create staff user
        $staffUser = new User();
        $staffUser->setUsername('staff');
        $staffUser->setPassword($this->encoder->encodePassword($adminUser, 'staff'));
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
            $randomUser->setPassword($this->encoder->encodePassword($randomUser, $randomUsername));
            // Commiting the object to database
            $manager->persist($randomUser);
            $manager->flush();
        }
// Begin House, Sponsor and Comment creation block
        // Random number of houses and sponsors, between 20 and 30
        $maxNumberOfHousesAndSponsors = rand(20, 30);
        for ($y = 0; $y < $maxNumberOfHousesAndSponsors; $y++) {

            $sponsor = new Sponsor();
            $sponsor->setName($this->faker->company);
            $sponsor->setProfile($this->faker->text(100));
            // Commiting the object to database
            $manager->persist($sponsor);
            $manager->flush();

            $house = new House();
            $house->setName($this->faker->company);
            $house->setDescription($this->faker->text(100));
            // Randomly associate house with the latest sponsor
            if (rand(0, 1) == 0) {
                $house->setSponsor($sponsor);
            }
            // Commiting the object to database
            $manager->persist($house);
            $manager->flush();

            // Random number of comments, between 10 and 30
            $maxNumberOfComments = rand(10, 30);
            for ($i = 0; $i < $maxNumberOfComments; $i++) {
                $comment = new Comment();
                $comment->setContent($this->faker->text(100));
                $comment->setApproved($this->faker->boolean);
                $comment->setCreated($this->faker->dateTime);
                $comment->setHouse($house);
                // Commiting the object to database
                $manager->persist($comment);
                $manager->flush();
            }
        }
    }
}