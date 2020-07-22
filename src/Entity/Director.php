<?php

namespace App\Entity;

use App\Repository\DirectorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DirectorRepository::class)
 */
class Director extends Employment
{

}
