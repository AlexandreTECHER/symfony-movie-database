<?php

namespace App\Command;

use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MoviesFindPostersCommand extends Command
{
    protected static $defaultName = 'app:movies:find-posters';

    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        parent::__construct();
        $this->movieRepository = $movieRepository;
    }


    protected function configure()
    {
        $this
            ->setDescription('Appelle l\'OMDb API pour aller chercher les affiches de tous les films')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // On veut tous les films
        // Pour chaque film, on fait un requête vers l'API qui nous permet de récupérer le Poster
        // On prend l'URL du poster pour télécharger l'image et la mettre dans un dossier
        
        $movies = $this->movieRepository->findBy([],[],10);
        
        // On parcourt chacun des films
        foreach ($movies as $movie) {
            // On demande à OMDB de nous donner l'url du poster du film
            $url = 'http://www.omdbapi.com/?apikey=45df58a5&type=movie&s=' . str_replace(' ', '%20', $movie->getTitle());
            $json = file_get_contents($url);

            // $json est une chaine de caractères en JSON, on cherche plutôt à manipuler un objet
            // On le désérialise
            $omdbAnswer = json_decode($json);

            if ($omdbAnswer->Response === 'True') {
                // On cherche à récupérer l'URL du poster
                $posterUrl = $omdbAnswer->Search[0]->Poster;

                // Éventuellement, le film n'a pas de poster, on reçoit alors "N/A"
                // Dans ce cas, on ne fait rien
                if ($posterUrl != 'N/A') {
                    // On a supposément un URL utilisable
                    // On télécharge le fichier et on le place dans le dossier des images
                    file_put_contents(
                        __DIR__.'/../../public/uploads/images/'.$movie->getId().'.jpg',
                        fopen($posterUrl, 'r')
                    );
                }
            }
        }
        
        $io = new SymfonyStyle($input, $output);
        $io->success('(nombre de nouveaux posters) films ont un nouveau poster dans le dossier /uploads/images');

        return 0;
    }
}
