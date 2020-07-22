<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
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
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies", cascade={"persist"})
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity=Employment::class, mappedBy="movie", orphanRemoval=true)
     */
    private $employees;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->genres = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->employees = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if($this->genres === null){
            $this->genres = new ArrayCollection();
        }

        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

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
            $employment->setMovie($this);
        }

        return $this;
    }

    public function removeEmployment(Employment $employment): self
    {
        if ($this->employments->contains($employment)) {
            $this->employments->removeElement($employment);
            // set the owning side to null (unless already changed)
            if ($employment->getMovie() === $this) {
                $employment->setMovie(null);
            }
        }

        return $this;
    }
}
