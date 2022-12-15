<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('root')
        ->setEmail('root@example.com')
        ->setPassword($this->hasher->hashPassword($user, 'test'))
        ->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $faker = Faker\Factory::create('fr-FR');

        for ($i=0;$i<5;$i++) {
            $user = new User();
        $user->setUsername($faker->userName())
        ->setEmail($faker->email())
        ->setPassword($this->hasher->hashPassword($user, 'test'))
        ->setRoles(['ROLE_USER']);

        $manager->persist($user);
        }

        for ($i=0;$i<5;$i++) {
            $user = new User();
        $user->setUsername($faker->userName())
        ->setEmail($faker->email())
        ->setPassword($this->hasher->hashPassword($user, 'test'))
        ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->addReference('admin'.$i, $user);

        $manager->persist($user);
        }

        $manager->flush();
    }
}
