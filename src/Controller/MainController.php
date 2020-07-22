<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\CrewMember;
use App\Entity\Director;
use App\Entity\Employment;
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
        // $movies = $movieRepository->findBy([], ['title' => 'ASC']);

        $movies = $movieRepository->findOrderByTitle();

        return $this->render('main/index.html.twig', [
            
            'movies' => $movies,
        
        ]);
    }

    /**
     * @Route("/movie/{id}", name="movie_single")
     */
    public function singleMovie(Movie $movie, MovieRepository $movieRepository)
    {
        $movie = $movieRepository->findComplete($movie);
         
        return $this->render('main/single.html.twig', [
            
            'movie' => $movie,
        
        ]);
    }

    /**
     * @Route("/demo/employment/{id}", name="demo_doctrine_employment")
     */
    public function showEmployment(Employment $employment)
    {
        if($employment instanceof Director){
            dd('c\'est un réalisateur');
        }
        if($employment instanceof Actor){
            dd('c\'est un acteur');
        }
        if($employment instanceof CrewMember){
            dd('c\'est un membre de l\'équipe');
        }

    }

}
