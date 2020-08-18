<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\MovieGenreProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class Faker extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Pour utiliser Faker, on a besoin d'un générateur
        // La méthode statique create() de Factory crée un objet de la classe Generator
        $generator = Factory::create('fr_FR');

        // On jaoute notre Provider fait maison au générateur
        $generator->addProvider(new MovieGenreProvider($generator));

        // On aussi besoin d'un populator
        // C'est une classe fournie par Faker, qui fait le lien entre
        // les entités de Doctrine et Faker
        $populator = new Populator($generator, $manager);

        // Grâce au Populator, on va créer des entités
        // et pour chaque propriété, on va préciser quelle méthode de $generator on veut utiliser
        $populator->addEntity('App\Entity\Genre', 20, [
            'name' => function() use ($generator) { return $generator->movieGenre(); },
        ]);

        $populator->addEntity('App\Entity\Person', 200, [
            'name' => function() use ($generator) { return $generator->unique()->name(); },
        ]);

        $populator->addEntity('App\Entity\Movie', 60, [
            'title' => function() use ($generator) { return $generator->unique()->movieTitle(); },
        ]);

        $populator->addEntity('App\Entity\Casting', 400, [
            'creditOrder' => function() use ($generator) { return $generator->numberBetween(1, 20); },
            'role' => function() use ($generator) { return $generator->firstName(); },
        ]); 

        $createdEntities = $populator->execute();

        // Faker sait bien gérer les relation ManyToOne/OneToMany mais pas les ManyToMany
        // On doit donc les faire à la main :

        $movies = $createdEntities['App\Entity\Movie'];
        $genres = $createdEntities['App\Entity\Genre'];

        foreach ($movies as $movie) {

            // On brasse notre tableau, tous les genres sont bousculés
            shuffle($genres);

            // Les trois premiers index de $genres sont désormais des Genre au hasard
            $movie->addGenre($genres[0]);
            $movie->addGenre($genres[1]);
            $movie->addGenre($genres[2]);

            // $manager->persist($movie);
        }

        $manager->flush();
    }
}
