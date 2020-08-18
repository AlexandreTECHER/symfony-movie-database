<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActorRepository")
 */
class Actor extends Employment
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("movie")
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     * @Groups("movie")
     */
    private $creditOrder;

    public function __toString()
    {
        return $this->role;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }
}
