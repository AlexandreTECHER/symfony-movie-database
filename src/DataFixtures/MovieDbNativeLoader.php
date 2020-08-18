<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\MovieGenreProvider;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;

class MovieDbNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = FakerGeneratorFactory::create('fr_FR');
        // On ajoute le provider fournit par NelmioAliceBundle
        $generator->addProvider(new AliceProvider());

        // On ajoute notre nouveau provider en passant le generator dans le constructeur de notre classe (heritée du parent base)
        $generator->addProvider(new MovieGenreProvider($generator));
        $generator->seed($this->getSeed());

        return $generator;
    }
}
