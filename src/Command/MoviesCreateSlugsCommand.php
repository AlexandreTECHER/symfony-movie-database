<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MoviesCreateSlugsCommand extends Command
{
    // Pour utiliser des classes à l'extérieur de la commande
    // on fait une injection de dépendances
    private $em;
    private $movieRepository;
    private $slugger;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, Slugger $slugger)
    {
        parent::__construct();
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->slugger = $slugger;
    }

    protected static $defaultName = 'app:movies:create-slugs';

    protected function configure()
    {
        $this
            ->setDescription('Calcule et met à jour le slug de tous les Movie qui n\'en ont pas')
            ->addArgument('movieId', InputArgument::OPTIONAL, 'The ID of the only Movie you want to update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $movieId = $input->getArgument('movieId');

        if ($movieId) {
            // On est dans le cas particulier où on voudrait modifier un seul film
            // On doit aller le chercher en BDD, modifier et le flush
            // Puis on retourn 0 pour arrêter l'exécution de la commande

            $movie = $this->movieRepository->find($movieId);
            
            // Si l'id n'existe en BDD, $movie vaut null
            if ($movie === null) {
                $io->error('Le film avec l\'id '.$movieId.' n\'existe pas.');
                return 0;                
            }

            $title = $movie->getTitle();
            $slug = $this->slugger->slugify($title);
            $movie->setSlug($slug);

            $this->em->flush();

            $io->success('Le film '.$movie->getTitle().' a désormais un slug');
            return 0;
        }

        // Notre objectif est de récupérer tous les films qui n'ont pas de slug
        // Boucler sur la liste de ces films, calculer leur slug et flush les modifications
        $movies = $this->movieRepository->findBy(['slug' => null]);

        foreach ($movies as $movie) {
            $title = $movie->getTitle();
            $slug = $this->slugger->slugify($title);
            $movie->setSlug($slug);
        }

        $this->em->flush();

        $io->success('Bravo ! Tous les films ont désormais un slug');

        // 0 est la valeur que le terminal attend de la part d'une commande pour annoncer que le travail est fini et que tout s'est bien passé
        return 0;
    }
}
