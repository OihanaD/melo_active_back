<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use App\Entity\ClientsCoachingSession;
use App\Entity\Coach;
use App\Entity\CoachingSession;
use App\Entity\User;
use App\Repository\CoachRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void

    {
        $coach = new Coach;
        $coach->setInformation("Information du coach");

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName("User " . $i);
            $user->setEmail("user" . $i . "@example.com");
            $user->setImage("user_" . $i . ".jpg");
            $user->setAddress(rand(1, 100) . " Main St, Anytown France");
            $user->setPassword("password_" . $i);
            if ($i == 1) {
                $coach->setUser($user);
            }
            $session = new CoachingSession;
            $year = date('Y'); // Get the current year

            $randomTimestamp = rand(strtotime($year . '-01-01'), strtotime($year . '-12-31'));
            $randomDateTime = new DateTime();
            $randomDateTime->setTimestamp($randomTimestamp);

            $session->setPrice(0); // Set a default value for price
            $session->setActivitySession("default");


            $session->setDateSession($randomDateTime);

            $client = new Clients;
            if($i != 1){
                $client->setUser($user);

            }

            $sessionClient = new ClientsCoachingSession;
            $sessionClient->setClientId($client);
            $sessionClient->setCoachingSessionId($session);
            $sessionClient->setIsPaid($i % 2 == 0); // Set is_paid to true or false depending on the value of $i


            if ($i % 2) {
                $client->setActivity("Sport");
                $client->setRepetitionPerMonth(8);
                $session->setActivitySession("Sport");
                $session->setPrice(20);
            } else {
                $client->setActivity("Nutrition");
                $client->setRepetitionPerMonth(4);
                $session->setActivitySession("Nutrition");
                $session->setPrice(30);
            }
            $session->setCoach($coach);

            $manager->persist($coach);

            $manager->persist($user);
            $manager->persist($session);
            $manager->persist($client);
            $manager->persist($sessionClient);
        }
        $manager->flush();
    }
}
