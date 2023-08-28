<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Clients;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class CreateClientController extends AbstractController
{
    public function __invoke(Request $request,ManagerRegistry $doctrine)
    {   
        $response = json_decode($request->getContent(), true);
        $nom = $response['user']['name'];
        $mail = $response['user']['email'];
        $address = $response['user']['address'];
        $phone = $response['user']['phone'];
        $activity = $response['activity'];
        $objectives = $response['objectives'];
        $problems = $response['problems'];
        $repetitionPerMonth = $response['repetitionPerMonth'];
        $user = new User();
        $user->setName($nom);
        $user->setEmail($mail);
        $user->setAddress($address);
        $user->setPhone($phone);
        $user->setPassword('MeloActiv');
        
        $client = new Clients();

        $client->setActivity($activity);
        $objectives? $client->setObjectives($objectives):$client->setObjectives(NULL);
        $problems? $client->setProblems($problems): $client->setProblems(NULL);
        $client->setRepetitionPerMonth($repetitionPerMonth);
        $user->setUserclient($client);

        $manager = $doctrine->getManager(); 
        $manager->persist($user);
        $manager->persist($client);
        $manager->flush();

        // Répondez avec une réponse de succès
        return new Response('Client créé avec succès', Response::HTTP_CREATED);



        
    }
}
