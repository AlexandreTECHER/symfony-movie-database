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
     * @Route("/{id}", name="read", requirements={"id" : "\d+"})
     */
    public function read(Movie $movie)
    {
        return $this->render('admin/movie/read.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     */
    public function edit(Movie $movie, Request $request)
    {

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $movie->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->flush();

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

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('admin_movie_browse');
        }

        return $this->render('admin/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(Movie $movie)
    {

        $em = $this->getDoctrine()->getManager();

        $em->remove($movie);
        $em->flush();

        return $this->redirectToRoute('admin_movie_browse');

    }

}
