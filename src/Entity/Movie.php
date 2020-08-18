<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
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
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("movie")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("movie")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="movies", cascade={"persist"})
     * @Groups("movie")
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Employment", mappedBy="movie", orphanRemoval=true)
     * @Groups("movie")
     */
    private $employees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("movie")
     */
    private $slug;

    public function __construct()
    {
        $this->castings = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->genres = new ArrayCollection();
        $this->employees = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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
        if ($this->genres === null) {
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
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employment $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setMovie($this);
        }

        return $this;
    }

    public function removeEmployee(Employment $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getMovie() === $this) {
                $employee->setMovie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * On peut créer les méthodes qu'on veut pour afficher des données en JSON
     * On crée ici un getter qui retourne une donnée qui nous arrange
     * 
     * @Groups("movie")
     */
    public function getNumberEmployees()
    {
        return count($this->employees);
    }
}
