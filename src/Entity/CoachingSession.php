<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CoachingSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateSeanceController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: CoachingSessionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['coachingSession:read']],
    denormalizationContext: ['groups' => ['coachingSession:write']],
    operations:[
        new Get(),
        new Put(),
        new Delete(),
        new Patch(),
        new Post(),
    ]
)]
class CoachingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?\DateTimeInterface $date_session = null;

    #[ORM\Column]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?string $activity_session = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?string $recap_of_coaching = null;

    #[ORM\ManyToOne(inversedBy: 'coaching_session')]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?Coach $coach = null;

    #[ORM\OneToMany(mappedBy: 'coachingSessionId', targetEntity: ClientsCoachingSession::class, cascade: ['persist'])]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private Collection $clientsCoachingSessions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['coachingSession:add:read', 'coachingSession:add:write'])]
    private ?string $objectif_of_coaching = null;

    public function __construct()
    {
        $this->clientsCoachingSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSession(): ?\DateTimeInterface
    {
        return $this->date_session;
    }

    public function setDateSession(\DateTimeInterface $date_session): self
    {
        $this->date_session = $date_session;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getActivitySession(): ?string
    {
        return $this->activity_session;
    }

    public function setActivitySession(string $activity): self
    {
        $this->activity_session = $activity;

        return $this;
    }

    public function getRecapOfCoaching(): ?string
    {
        return $this->recap_of_coaching;
    }

    public function setRecapOfCoaching(?string $recap_of_coaching): self
    {
        $this->recap_of_coaching = $recap_of_coaching;

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): self
    {
        $this->coach = $coach;

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
            $clientsCoachingSession->setCoachingSessionId($this);
        }

        return $this;
    }

    public function removeClientsCoachingSession(ClientsCoachingSession $clientsCoachingSession): self
    {
        if ($this->clientsCoachingSessions->removeElement($clientsCoachingSession)) {
            // set the owning side to null (unless already changed)
            if ($clientsCoachingSession->getCoachingSessionId() === $this) {
                $clientsCoachingSession->setCoachingSessionId(null);
            }
        }

        return $this;
    }

    public function getObjectifOfCoaching(): ?string
    {
        return $this->objectif_of_coaching;
    }

    public function setObjectifOfCoaching(?string $objectif_of_coaching): self
    {
        $this->objectif_of_coaching = $objectif_of_coaching;

        return $this;
    }
}
