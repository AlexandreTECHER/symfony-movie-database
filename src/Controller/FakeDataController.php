<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FakeDataController extends AbstractController
{
    /**
     * @Route("/fake ", name="fake_data")
     */
    public function index(EntityManagerInterface $em)
    {

        $genres = ['horreur', 'comédie', 'thriller', 'drame', 'fantastique', 'science-fiction', 'aventure', 'action'];
        $genreEntities = [];

        foreach($genres as $genreName){

            $genre = new Genre();
            $genre->setName($genreName);
            $em->persist($genre);
            $genreEntities[] = $genre;


        }

        $people = ['Michel', 'Jacquelin', 'Ginette', 'Marcel', 'Prosperine', 'Victorinne'];
        $peopleEntities = [];
        
        foreach($people as $personName){

            $person = new Person();
            $person->setName($personName);
            $em->persist($person);

            $peopleEntities[] = $person;

        }

        $movies = ['Titanic', 'American Beauty', 'Pulp Fiction', 'L\'aile ou la cuisse', '1917'];
        $moviesEntities = [];

        foreach($movies as $movieTitle){

            $movie = new Movie();
            $movie->setTitle($movieTitle);
            $movie->addGenre($genreEntities[mt_rand(0, 7)]);
            $movie->addGenre($genreEntities[mt_rand(0, 7)]);
            $em->persist($movie);

            $moviesEntities[] = $movie;
        }

        $roles = ['Superman', 'Mrs Doubtfire', 'Le génie', 'Ace Ventura', 'Criquette', 'Lester', 'Neo', 'Morpheus', 'Marty', 'Emmett'];

        foreach($roles as $role){
            $casting = new Casting();
            $casting->setRole($role);
            $casting->setCreditOrder(mt_rand(1,32));
            $casting->setMovie($moviesEntities[mt_rand(0,3)]);
            $casting->setPerson($peopleEntities[mt_rand(0,5)]);

            $em->persist($casting);

        }

        $em->flush();

        return $this->render('fake_data/index.html.twig', [
            'controller_name' => 'FakeDataController',
        ]);
    }
}
