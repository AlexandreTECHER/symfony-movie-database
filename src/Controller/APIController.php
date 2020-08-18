<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/movies", name="movies_browse", methods={"GET"})
     */
    public function browseMovies(MovieRepository $movieRepository, SerializerInterface $serializer)
    {
        // Récupérons un seul film, peu importe lequel
        $movie = $movieRepository->findOneBy([]);

        // On utilise le Serializer pour normaliser notre objet Movie
        $data = $serializer->normalize($movie, null, ['groups' => 'movie']);

        return $this->json($data);
    }

    /**
     * @Route("/movies/{id}", name="movies_read", methods={"GET"})
     */
    public function readMovie(Movie $movie)
    {
        // Ici on devrait normaliser puis envoyer une réponse en JSON
    }

    /**
     * @Route("/movies", name="movies_new", methods={"POST"})
     */
    public function addMovie()
    {
        // Ici on récupèrerait les données reçues en JSON
        // On s'en sert pour créer un nouveau Movie qu'on persiste et qu'on flushe
        // On normalise le nouvel objet puis on l'envoie en JSON, si possible avec un code de réponse 201
    }

}
