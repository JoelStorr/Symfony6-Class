<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{


    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
        
    }
    public function load(ObjectManager $manager): void
    {


        # Add User
        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                '123456789'
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('john@test.com');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2,
                '123456789'
            )
        );
        $manager->persist($user2);




        # Add Dummy Posts
        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welcome to Germany!');
        $microPost1->setText('Welcome to Germany!');
        $microPost1->setCreated(new DateTime());
        $manager->persist($microPost1); // Stages Data to be Added to Table

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welcome to US!');
        $microPost2->setText('Welcome to US!');
        $microPost2->setCreated(new DateTime());
        $manager->persist($microPost2); // Stages Data to be Added to Table

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welcome to Poland!');
        $microPost3->setText('Welcome to Poland!');
        $microPost3->setCreated(new DateTime());
        $manager->persist($microPost3); // Stages Data to be Added to Table



        $manager->flush(); //Adds Data to Table
    }
}
