<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActivityMember", mappedBy="activity")
     */
    private $activityMembers;

    public function __construct()
    {
        $this->activityMembers = new ArrayCollection();
    }
    /**
     * @return Collection|ActivityMember[]
     */
    public function getActivityMembers(): Collection
    {
        return $this->activityMembers;
    }

    public function addActivityMember(ActivityMember $activityMember): self
    {
        if (!$this->activityMembers->contains($activityMember)) {
            $this->activityMembers[] = $activityMember;
            $activityMember->setActivity($this);
        }

        return $this;
    }

    public function removeActivityMember(ActivityMember $activityMember): self
    {
        if ($this->activityMembers->removeElement($activityMember)) {
            // set the owning side to null (unless already changed)
            if ($activityMember->getActivity() === $this) {
                $activityMember->setActivity(null);
            }
        }

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }


    public function setActivityMembers($activityMembers)
    {
        $this->activityMembers = $activityMembers;
    }
}
