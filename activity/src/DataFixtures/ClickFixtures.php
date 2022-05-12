<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\Click;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClickFixtures extends Fixture
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach (range(1, 9) as $i) {
            $date = new \DateTimeImmutable('2022-01-01 10:10:0'.$i);

            $click = new Click();
            $click
                ->setUrl('/path/'.$i)
                ->setCounter(2)
                ->setLastVisit($date)
                ;

            $manager->persist($click);
        }

        $manager->flush();
    }
}
