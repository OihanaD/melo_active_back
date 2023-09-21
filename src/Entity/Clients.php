<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\ClientInfosForListController;
use App\Controller\CreateClientController;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ClientsRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Put(),
        new Delete(),
        new GetCollection(name: 'clientsInfos', uriTemplate: 'client/infos', controller: ClientInfosForListController::class),
        new GetCollection(),
        new Post(),
        new Post(name: 'createClient', uriTemplate: 'client/add', controller: CreateClientController::class)
        // , openapiContext: [
        //     'requestBody' => [
        //         'content' => [
        //             'multipart/form-data' => [
        //                 'schema' => [
        //                     'type' => 'object',
        //                     'properties' => [
        //                         'file' => [
        //                             'type' => 'string',
        //                             'format' => 'binary'
        //                         ]
        //                     ]
        //                 ]
        //             ]
        //         ]
        //     ]
        // ]),
        ,
        new Patch()
    ]
)]

class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $activity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objectives = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $problems = null;

    #[ORM\OneToOne(mappedBy: 'userclient', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $repetition_per_month = null;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: ClientsCoachingSession::class)]
    private Collection $clientsCoachingSessions;

    public function __construct()
    {
        $this->clientsCoachingSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(?string $objectives): self
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getProblems(): ?string
    {
        return $this->problems;
    }

    public function setProblems(?string $problems): self
    {
        $this->problems = $problems;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setUserclient(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getUserclient() !== $this) {
            $user->setUserclient($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getRepetitionPerMonth(): ?int
    {
        return $this->repetition_per_month;
    }

    public function setRepetitionPerMonth(int $repetition_per_month): self
    {
        $this->repetition_per_month = $repetition_per_month;

        return $this;
    }



    /**
     * @return Collection<int, ClientsCoachingSession>
     */
    public function getClientsCoachingSessions(): Collection
    {
        return $this->clientsCoachingSessions;
    }

    public function addClientsCoachingSession(ClientsCoachingSession $clientsCoachingSession): self
    {
        if (!$this->clientsCoachingSessions->contains($clientsCoachingSession)) {
            $this->clientsCoachingSessions->add($clientsCoachingSession);
            $clientsCoachingSession->setClientId($this);
        }

        return $this;
    }

    public function removeClientsCoachingSession(ClientsCoachingSession $clientsCoachingSession): self
    {
        if ($this->clientsCoachingSessions->removeElement($clientsCoachingSession)) {
            // set the owning side to null (unless already changed)
            if ($clientsCoachingSession->getClientId() === $this) {
                $clientsCoachingSession->setClientId(null);
            }
        }

        return $this;
    }
}
