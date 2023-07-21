<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CoachingSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachingSessionRepository::class)]
#[ApiResource]
class CoachingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_session = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $activity_session = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $recap_of_coaching = null;

    #[ORM\ManyToOne(inversedBy: 'coaching_session')]
    private ?Coach $coach = null;

    #[ORM\OneToMany(mappedBy: 'coachingSessionId', targetEntity: ClientsCoachingSession::class)]
    private Collection $clientsCoachingSessions;

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
}
