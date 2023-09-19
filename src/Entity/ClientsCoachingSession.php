<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClientsCoachingSessionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\ClientDataController;
use App\Controller\CreateSeanceController;
use App\Controller\InformationsController;
use App\Controller\PaymentsController;
use App\Controller\TotalPaymentsController;
use App\Controller\TotalPaymentsWaitingController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientsCoachingSessionRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new Put(),
        new Delete(),
        new GetCollection(name: 'friendsAndGroups', uriTemplate: '/informations/{year}/{month}/{firstday}', controller: InformationsController::class),
        new GetCollection(name: 'payments', uriTemplate: '/payments', controller: PaymentsController::class),
        new GetCollection(name: 'paymentsTotalPerMonthPayed', uriTemplate: '/payments/total/{month}/{year}', controller: TotalPaymentsController::class),
        new GetCollection(name: 'paymentsTotalwaiting', uriTemplate: '/payments/total/wait', controller: TotalPaymentsWaitingController::class),
        new GetCollection(name: 'clientData', uriTemplate: '/client/details/{id}', controller: ClientDataController::class),
        new GetCollection(),
        new Post(), 
        new Patch()
    ]
)]
class ClientsCoachingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_paid = null;

    #[ORM\ManyToOne(inversedBy: 'clientsCoachingSessions')]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?Clients $clientId = null;

    #[ORM\ManyToOne(inversedBy: 'clientsCoachingSessions')]
    private ?CoachingSession $coachingSessionId = null;


    public function __construct()
    {
 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsPaid(): ?bool
    {
        return $this->is_paid;
    }

    public function setIsPaid(bool $is_paid): self
    {
        $this->is_paid = $is_paid;

        return $this;
    }

    public function getClientId(): ?Clients
    {
        return $this->clientId;
    }

    public function setClientId(?Clients $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getCoachingSessionId(): ?CoachingSession
    {
        return $this->coachingSessionId;
    }

    public function setCoachingSessionId(?CoachingSession $coachingSessionId): self
    {
        $this->coachingSessionId = $coachingSessionId;

        return $this;
    }

   
}
