<?php
namespace App\Service;

use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

class Slugger
{
    private $em;
    private $movieRepository;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
    }

    /**
     * @return string
     */
    public function slugify(string $str)
    {
        $rawSlug = preg_replace( '/[^a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*/', '-', strtolower(trim(strip_tags($str))));

        // On cherche à enlever des tirets au début ou à la fin de la chaine de caractères
        $arraySlug = str_split($rawSlug);
        $firstIndex = 0;
        $lastIndex = count($arraySlug) - 1;

        // On teste le tout premier caractère, si c'est un tiret, on l'enlève
        if ($arraySlug[0] == '-') {
            unset($arraySlug[0]);
        }
        // On teste le derneir caractère, si c'est un tiret, on l'enlève
        if ($arraySlug[$lastIndex] == '-') {
            unset($arraySlug[$lastIndex]);
        }

        return implode($arraySlug);
    }

    /**
     * Reads all movies. creates slugs and save them
     * @return null
     */
    public function slugifyAllMovies()
    {
        // Cette méthode doit servir à créer le slug de tous les films et le mettre en base de données
        // Ça veut dire qu'on a besoin d'un moyen de récupérer tous les films
        // On pourra ensuite faire une boucle pour créer le slug
        // Puis attribuer le slug à chaque film
        // et flush les modifications

        $movies = $this->movieRepository->findAll();

        foreach($movies as $movie) {
            // On obtient le slug à partir du titre
            $title = $movie->getTitle();
            $slug = $this->slugify($title);
            // On set le slug pour le film
            $movie->setSlug($slug);
        }

        // On flush les modifications
        $this->em->flush();
    }
}