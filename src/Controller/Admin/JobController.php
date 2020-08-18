<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/job", name="admin_job_")
 */
class JobController extends AbstractController
{
    /**
     * @Route("/", name="browse")
     */
    public function browse(JobRepository $jobRepository)
    {
        return $this->render('admin/job/browse.html.twig', [
            'jobs' => $jobRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id": "\d+"})
     */
    public function read(Job $job)
    {
        return $this->render('admin/job/read.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     */
    public function edit(Job $job, Request $request)
    {
        // Pour éditer l'entité, il nous un formulaire
        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On peut maintenant modifier la propriété updatedAt de $job
            $job->setUpdatedAt(new \DateTime());

            // Notre entité est modifié, on peut flush
            $this->getDoctrine()->getManager()->flush();

            // On redirige vers la liste des Jobs
            return $this->redirectToRoute('admin_job_browse');
        }

        return $this->render('admin/job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            $em->persist($job);
            $em->flush();

            // On redirige vers la liste des Jobs
            return $this->redirectToRoute('admin_job_browse');
        }

        return $this->render('admin/job/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}, methods={"DELETE"})
     */
    public function delete(Job $job)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($job);
        $em->flush();

        return $this->redirectToRoute('admin_job_browse');
    }
}
