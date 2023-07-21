<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
#[ApiResource]
class Coach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $information = null;

    #[ORM\OneToOne(mappedBy: 'usercoach', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'coach', targetEntity: CoachingSession::class)]
    private Collection $coaching_session;

    public function __construct()
    {
        $this->coaching_session = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

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
            $this->user->setUsercoach(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getUsercoach() !== $this) {
            $user->setUsercoach($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, CoachingSession>
     */
    public function getCoachingSession(): Collection
    {
        return $this->coaching_session;
    }

    public function addCoachingSession(CoachingSession $coachingSession): self
    {
        if (!$this->coaching_session->contains($coachingSession)) {
            $this->coaching_session->add($coachingSession);
            $coachingSession->setCoach($this);
        }

        return $this;
    }

    public function removeCoachingSession(CoachingSession $coachingSession): self
    {
        if ($this->coaching_session->removeElement($coachingSession)) {
            // set the owning side to null (unless already changed)
            if ($coachingSession->getCoach() === $this) {
                $coachingSession->setCoach(null);
            }
        }

        return $this;
    }
}
