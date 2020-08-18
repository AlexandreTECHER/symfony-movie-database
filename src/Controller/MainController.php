<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(MovieRepository $movieRepository)
    {
        // On va chercher tous les films sans se poser de question
        // On aura la liste des films très certainement par ordre des id
        // $movies = $movieRepository->findAll();

        // Pour avoir la liste des films par ordre alphabétique, plusieurs options s'offrent à nous
        // $movies = $movieRepository->findBy([], ['title' => 'ASC']);

        // On peut aussi créer une méthode du repository avec la requête qu'on veut dedans
        $movies = $movieRepository->findOrderByTitle();

        return $this->render('main/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/movie/{id}", name="movie_single", requirements={"id": "\d+"})
     */
    public function singleMovie(MovieRepository $movieRepository, $id)
    {
        $movie = $movieRepository->findComplete($id);

        return $this->render('main/single.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/movie/{slug}", name="movie_single_slug")
     */
    public function singleMovieWithSlug(Movie $movie)
    {
        return $this->render('main/single.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/demo-qb")
     */
    public function demoQb(EntityManagerInterface $em)
    {
        // Jouons avec le QueryBuilder
        // D'abord il faut le créer
        $qb = $em->createQueryBuilder();
        // Si on crée le QueryBuilder à partir dûn contrôleur, il est nécessaire de préciser le select() et le from()
        // Ensuite, on va préciser toutes les parties de la requête SQL à venir avec des méthodes
        // de la classe QueryBuilder
        // Remarquez ici qu'on n'appelle pas $qb à chaque fois.
        // On pourrait le faire, mais chaque méthode de $qb retourne $qb modifié par la méthode 
        // La plupart des QB que vous verrez seront écrites de cette façon (cf les exemples dans les Repository)
        $qb
            ->select('m, g, c, p') // On précise qu'on veut sélectionne les Movie, Genre, Casting et Person
            ->from('App\Entity\Movie', 'm') // …à partir de la table de l'entité Movie
            ->join('m.genres', 'g') // On fait une jointure de Movie vers Genre grâce à sa relation
            // Les jointures sont faites automatiquement sans avoir à préciser la table
            // ni préciser sur quels chammps se font les jointures
            ->join('m.castings', 'c') // On joint donc les Casting aux Movie
            ->join('c.person', 'p') // On joint les Person aux Casting
            ->where('m.id = 1') // Là où l'id de Movie vaut 1
        ; // On exécuté plein de méthodes sur $qb, il faut tout de même terminer l'instruction ("la ligne"), avec un point-virgule

        dd($qb, $qb->getQuery(), $qb->getQuery()->getResult());
    }

    
}
