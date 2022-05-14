<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (range(1, 10) as $_) {
            $news = new News();

            $news
                ->setTitle($faker->catchPhrase())
                ->setDate($faker->dateTimeBetween('-30 days', 'now'))
                ->setText($faker->text(1000))
            ;

            $manager->persist($news);
        }

        $manager->flush();
    }
}
