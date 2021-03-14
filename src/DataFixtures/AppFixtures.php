<?php

namespace App\DataFixtures;

use App\Entity\Race;
use App\Entity\RaceResult;
use App\Entity\Runner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $runners = [];
        for ($i = 0; $i < 50; $i++) {
            $runner = new Runner();
            $runner->setName('Name example ' . $i);
            $runner->setCpf('123456123' . $i);
            $runner->setBirthdate(
                date_sub(
                    new \DateTime(),
                    new \DateInterval('P' . rand(1, 10) * 5 . 'Y')
                )
            );

            $runners[] = $runner;

            $manager->persist($runner);
        }

        for ($i = 0; $i < 10; $i++) {
            $race = new Race();
            $race->setType(Race::VALID_TYPES[rand(0, 4)]);
            $race->setDate(date_add(
                new \DateTime('00:00:00'),
                new \DateInterval('P' . $i * 5 . 'M')
            ));

            for ($it = 0; $it < 5; $it++) {
                $race->addRunner($runners[$i * 5 + $it]);

                $resultStartDate = clone $race->getDate();
                $resultFinishDate = clone $race->getDate();

                $resultFinishDate->add(\DateInterval::createFromDateString(rand(60, 600) . ' seconds'));

                $raceResult = new RaceResult();
                $raceResult->setRace($race);
                $raceResult->setRunner($runners[$i * 5 + $it]);
                $raceResult->setStartTime($resultStartDate);
                $raceResult->setFinishTime($resultFinishDate);

                $manager->persist($raceResult);
            }

            $manager->persist($race);
        }

        $manager->flush();
    }
}
