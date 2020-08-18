<?php

namespace App\DataFixtures;

use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class NelmioAlice extends Fixture
{
    // S04E15 : Services
    // On découvre l'injection de dépendance et on a créé le service Slugger
    // On va donc injecter le Slugger dans nos fixtures pour l'utiliser avec de flush les films

    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $em)
    {
        // Initialement on utilise la classe NativeLoader
        // On a cependant étendu cette classe dans MovieDbNativeLoader
        // Ça nous permet d'avoir des résultats en français et 
        // d'utiliser notre provider fait maison : MovieGenreProvider

        // $loader = new NativeLoader();
        $loader = new MovieDbNativeLoader();
        
        //importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yaml')->getObjects();
        
        //empile la liste d'objet à enregistrer en BDD
        foreach ($entities as $entity) {
            // S04E15, on applique le slug à chaque Movie
            if ($entity instanceof \App\Entity\Movie) {
                $title = $entity->getTitle();
                $slug = $this->slugger->slugify($title);
                $entity->setSlug($slug);
            }

            $em->persist($entity);
        };
        
        //enregistre
        $em->flush();
    }
}
