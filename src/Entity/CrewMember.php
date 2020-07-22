<?php

namespace App\Entity;

use App\Repository\CrewMemberRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrewMemberRepository::class)
 */
class CrewMember extends Employment
{
    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="crewMembers")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $job;

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
