<?php

namespace App\Entity;

use App\Repository\ActivityMemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityMemberRepository::class)]
class ActivityMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="activityMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?int $member;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="activityMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?int $activity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?int
    {
        return $this->member;
    }

    public function setMember(int $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getActivity(): ?int
    {
        return $this->activity;
    }

    public function setActivity(int $activity): self
    {
        $this->activity = $activity;

        return $this;
    }
}
