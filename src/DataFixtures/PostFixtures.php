<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Faker;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr-FR');
        
        for ($i = 0; $i < 10; $i++) {
            $post = new Post;

            $post->setTitle($faker->words(rand(3,10), true))
            ->setDescription($faker->words(rand(10,40), true))
            ->setAuthor($faker->firstname())
            ->setImage('http://placeimg.com/30'.$i.'/30'.$i.'/any')
            ->setUser($this->getReference('admin0'));

            for ($j=0;$j<rand(1,3);$j++) {
                $post->addCategory($this->getReference('category'. rand(0,4)));
            }
        
            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}
