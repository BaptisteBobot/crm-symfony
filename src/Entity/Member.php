<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $role = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActivityMember", mappedBy="member")
     */
    private $activityMembers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyResponse", mappedBy="member")
     */
    private $surveyResponses;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Expense::class)]
    private Collection $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
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
            $activityMember->setMember($this);
        }

        return $this;
    }

    public function removeActivityMember(ActivityMember $activityMember): self
    {
        if ($this->activityMembers->removeElement($activityMember)) {
            // set the owning side to null (unless already changed)
            if ($activityMember->getMember() === $this) {
                $activityMember->setMember(null);
            }
        }

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isRole(): ?bool
    {
        return $this->role;
    }

    public function setRole(bool $role): self
    {
        $this->role = $role;

        return $this;
    }


    public function setActivityMembers(string $activityMembers): self
    {
        $this->activityMembers = $activityMembers;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setMember($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getMember() === $this) {
                $expense->setMember(null);
            }
        }

        return $this;
    }
}
