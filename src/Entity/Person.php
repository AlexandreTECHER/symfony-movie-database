<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
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
     * @ORM\OneToMany(targetEntity=Employment::class, mappedBy="person", orphanRemoval=true)
     */
    private $employments;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->castings = new ArrayCollection();
        $this->employments = new ArrayCollection();
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

    /**
     * @return Collection|Employment[]
     */
    public function getEmployments(): Collection
    {
        return $this->employments;
    }

    public function addEmployments(Employment $employment): self
    {
        if (!$this->employments->contains($employment)) {
            $this->employments[] = $employment;
            $employment->setPerson($this);
        }

        return $this;
    }

    public function removeEmployee(Employment $employment): self
    {
        if ($this->employments->contains($employment)) {
            $this->employments->removeElement($employment);
            // set the owning side to null (unless already changed)
            if ($employment->getPerson() === $this) {
                $employment->setPerson(null);
            }
        }

        return $this;
    }

}
