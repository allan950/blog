<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr-FR');

        for ($i=0; $i < 5; $i++) {

            $category = new Category;
            $category->setName($faker->words(1, true));
                    //->addPost($faker->words(1, true));

            $manager->persist($category);

            $this->addReference('category'.$i, $category);
        }

        $manager->flush();
    }
}
