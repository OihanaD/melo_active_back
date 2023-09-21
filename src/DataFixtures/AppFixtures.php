<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use App\Entity\ClientsCoachingSession;
use App\Entity\Coach;
use App\Entity\CoachingSession;
use App\Entity\User;
use App\Repository\CoachRepository;
use App\Service\Connexion;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = self::REFERENCE . 'admin';
    public const MAIL_HOST = '@exemple.fr';
    public const REFERENCE = 'user_';
    public const SUPER_ADMIN_USER_REFERENCE = self::REFERENCE . 'super_admin';
    public const TOTAL_FIXTURES = 5;
    public const USER_PASSWORD = 'user_password';

    public const ADMIN_FIXTURES = [
        [
            'reference' => self::ADMIN_USER_REFERENCE,
        ],
        [
            'reference' => self::SUPER_ADMIN_USER_REFERENCE,
        ],
    ];

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void

    {
        $coach = new Coach;
        $coach->setInformation("Information du coach");
       
        for ($i = 0; $i < 10; $i++) {
            $reference = self::REFERENCE . $i;
            $user = new User();

            $user->setEmail($reference . static::MAIL_HOST)
                ->setPassword($this->hasher->hashPassword($user, self::USER_PASSWORD))
                ->setName($reference)
                ->setAddress(rand(1, 100) . " Main St, Anytown France");
                

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
            $this->addReference($reference, $user);
            $manager->persist($session);
            $manager->persist($client);
            $manager->persist($sessionClient);
        }

        foreach (self::ADMIN_FIXTURES as $fixture) {
            $user = new User();

            $user->setEmail($fixture['reference'] . self::MAIL_HOST)
                ->setPassword($this->hasher->hashPassword($user, self::USER_PASSWORD))
                ->setName($fixture['reference']);

            $manager->persist($user);
            $this->addReference($fixture['reference'], $user);
        }

        $manager->flush();
    }
}
