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
     * @Route("/fake", name="fake_data")
     */
    public function index(EntityManagerInterface $em)
    {
        // On souhaite ajouter des fausses données, on va donc des Genre, des Movies, des Persons et des Castings de manière arbitraire
        // L'interêt de ce contrôleur c'est de ne pas avoir à faire de formulaire et que si on vide la base de données, on pourra recréer des données juste en allant sur /fake
        // Sur le projet final, ce contrôleur n'existera pas

        // Commençons par les Genre
        // On se créer une liste de genres
        $genres = ['horreur', 'comédie', 'thriller', 'drame', 'fantastique', 'science-fiction', 'aventure', 'action'];
        // On crée un tableau vide dans lequel on ajoutera chacun des objets qu'on aura créé
        $genresEntities = [];
        // On s'en sert ensuite pour créer des objets Genre et leur attribuer ces noms
        foreach($genres as $genreName) {
            $genre = new Genre();
            $genre->setName($genreName);
            $em->persist($genre);
            // On ajoute l'objet au tableau
            // Cette écriture ajoute une nouvelle entrée dans le tableau, pour ce nouvel index on retrouvera le nouvel objet qui était dans $genre
            $genresEntities[] = $genre;
        }

        $people = ['Michel', 'Jaquelin', 'Ginette', 'Marcel', 'Proserpine', 'Victorine'];
        $peopleEntities = [];
        foreach($people as $personName) {
            $person = new Person();
            $person->setName($personName);
            $em->persist($person);
            $peopleEntities[] = $person;
        }

        $movies=['titre 1', 'titre 2', 'titre 3', 'titre 4'];
        $moviesEntities = [];
        foreach($movies as $movieTitle) {
            $movie = new Movie();
            $movie->setTitle($movieTitle);

            // On attribue 2 genres au hasard à notre movie
            $movie->addGenre($genresEntities[mt_rand(0, 7)]);
            $movie->addGenre($genresEntities[mt_rand(0, 7)]);

            $em->persist($movie);

            $moviesEntities[] = $movie;
        }

        // On est prêts à créer des Casting
        $roles = ['Superman', 'Mrs Doubtfire', 'Le génie', 'Ace Ventura', 'Criquette', 'Monsieur', 'Madame', 'Thor', 'Milo', 'Hubert Bonnisseur de La Bath', 'Patrick', 'Macfly'];

        foreach($roles as $role) {
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
