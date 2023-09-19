<?php

namespace App\Controller;

use App\Entity\ClientsCoachingSession;
use App\Entity\CoachingSession;
use App\Entity\User;
use App\Repository\ClientsRepository;
use App\Repository\CoachRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CreateSeanceController extends AbstractController
{
    #[Route('api/session/adding', name: 'app_cs_add')]
    public function __invoke(Request $request, ManagerRegistry $doctrine, ClientsRepository $clientRepos, CoachRepository $coachRepository)
    {
 

            $response = json_decode($request->getContent(), true);
            $seancePrice = $response['price'];
            $seanceDateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $response['date']);
            
            $seanceActivity = $response['activity'];
            $seanceObjectif = $response['objectif'];

   
            $client = $response['clientId'];
            $client =  $clientRepos->find($client);

            $seance = new CoachingSession;
            $seance->setPrice($seancePrice);
            $seance->setDateSession($seanceDateTime);
            $seance->setActivitySession($seanceActivity);
            $seance->setObjectifOfCoaching($seanceObjectif);
            $coach = $coachRepository->findOneBy(['id' => 5]);
            $seance->setCoach($coach);
            $manager = $doctrine->getManager();

            $manager->persist($client);
            $manager->persist($seance);
            $manager->flush();


            $clientcoachingSession = new ClientsCoachingSession;
            $clientcoachingSession->setClientId($client);
            $clientcoachingSession->setCoachingSessionId($seance);
            $clientcoachingSession->setIsPaid(false);
           
            $manager->persist($clientcoachingSession);
            $manager->flush();
            return new JsonResponse('Nouvelle séance créé avec succès',200);
  
    }
}
