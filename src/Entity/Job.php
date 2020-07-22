<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="jobs")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=CrewMember::class, mappedBy="job")
     */
    private $crewMembers;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->crewMembers = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|CrewMember[]
     */
    public function getCrewMembers(): Collection
    {
        return $this->crewMembers;
    }

    public function addCrewMember(CrewMember $crewMember): self
    {
        if (!$this->crewMembers->contains($crewMember)) {
            $this->crewMembers[] = $crewMember;
            $crewMember->setJob($this);
        }

        return $this;
    }

    public function removeCrewMember(CrewMember $crewMember): self
    {
        if ($this->crewMembers->contains($crewMember)) {
            $this->crewMembers->removeElement($crewMember);
            // set the owning side to null (unless already changed)
            if ($crewMember->getJob() === $this) {
                $crewMember->setJob(null);
            }
        }

        return $this;
    }
}
