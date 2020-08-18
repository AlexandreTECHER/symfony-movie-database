<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CrewMemberRepository")
 */
class CrewMember extends Employment
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="crewMembers")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $job;

    public function __toString()
    {
        return $this->person->getName();
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }
}
