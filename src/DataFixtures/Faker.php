<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\MovieGenreProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class Faker extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('fr_FR');

        $generator->addProvider(new MovieGenreProvider($generator));

        $populator = new Populator($generator, $manager);

        $populator->addEntity('App\Entity\Genre', 20, [
            'name' => function() use($generator) {return $generator->movieGenre(); },
        ]);

        $populator->addEntity('App\Entity\Person', 700, [
            'name' => function() use($generator) {return $generator->unique()->name(); },
        ]);

        $populator->addEntity('App\Entity\Movie', 60, [
            'title' => function() use($generator) {return $generator->unique()->movieTitle(); },
        ]);

        $populator->addEntity('App\Entity\Casting', 400, [
            'creditOrder' => function() use($generator) {return $generator->numberBetween(1, 20); },
            'role' => function() use($generator) {return $generator->firstName(); },
        ]);

        $createdEntities = $populator->execute();
        
        $movies = $createdEntities['App\Entity\Movie'];
        $genres = $createdEntities['App\Entity\Genre'];

        foreach($movies as $movie){

            shuffle($genres);

            $movie->addGenre($genres[0]);
            $movie->addGenre($genres[1]);
            $movie->addGenre($genres[2]);

        }

        $manager->flush();
    }
}
