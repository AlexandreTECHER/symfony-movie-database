<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/movie", name="admin_movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="browse")
     */
    public function browse(MovieRepository $movieRepository)
    {
        return $this->render('admin/movie/browse.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id": "\d+"})
     */
    public function read(Movie $movie)
    {
        return $this->render('admin/movie/read.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     */
    public function edit(Movie $movie, Request $request)
    {
        // Pour éditer l'entité, il nous un formulaire
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On peut maintenant modifier la propriété updatedAt de $movie
            $movie->setUpdatedAt(new \DateTime());

            // Notre entité est modifié, on peut flush
            $this->getDoctrine()->getManager()->flush();

            // On redirige vers la liste des Movies
            return $this->redirectToRoute('admin_movie_browse');
        }

        return $this->render('admin/movie/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            $em->persist($movie);
            $em->flush();

            // On redirige vers la liste des Movies
            return $this->redirectToRoute('admin_movie_browse');
        }

        return $this->render('admin/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}, methods={"DELETE"})
     */
    public function delete(Movie $movie)
    {
        // Ici on utilise un voter
        // Cette fonction va émettre une exception Access Forbidden pour interdire l'accès au reste du contrôleur
        // Les conditions pour lesquelles le droit MOVIE_DELETE est applicable sur $movie pour l'utilisateur connecté
        // sont définies dans les voters, dans leurs méthodes voteOnAttribute()
        $this->denyAccessUnlessGranted('MOVIE_DELETE', $movie);

        $em = $this->getDoctrine()->getManager();

        $em->remove($movie);
        $em->flush();

        return $this->redirectToRoute('admin_movie_browse');
    }
}
