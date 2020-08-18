<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("movie")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("movie")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Employment", mappedBy="person", orphanRemoval=true)
     */
    private $employments;

    public function __construct()
    {
        $this->castings = new ArrayCollection();
        $this->createdAt = new \DateTime();
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

    public function addEmployment(Employment $employment): self
    {
        if (!$this->employments->contains($employment)) {
            $this->employments[] = $employment;
            $employment->setPerson($this);
        }

        return $this;
    }

    public function removeEmployment(Employment $employment): self
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
