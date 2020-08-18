<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/department", name="admin_department_")
 */
class DepartmentController extends AbstractController
{
    /**
     * @Route("/", name="browse")
     */
    public function browse(DepartmentRepository $departmentRepository)
    {
        return $this->render('admin/department/browse.html.twig', [
            'departments' => $departmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id": "\d+"})
     */
    public function read(Department $department)
    {
        return $this->render('admin/department/read.html.twig', [
            'department' => $department,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     */
    public function edit(Department $department, Request $request)
    {
        // Pour éditer l'entité, il nous un formulaire
        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On peut maintenant modifier la propriété updatedAt de $department
            $department->setUpdatedAt(new \DateTime());

            // Notre entité est modifié, on peut flush
            $this->getDoctrine()->getManager()->flush();

            // On redirige vers la liste des Departments
            return $this->redirectToRoute('admin_department_browse');
        }

        return $this->render('admin/department/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            $em->persist($department);
            $em->flush();

            // On redirige vers la liste des Departments
            return $this->redirectToRoute('admin_department_browse');
        }

        return $this->render('admin/department/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}, methods={"DELETE"})
     */
    public function delete(Department $department)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($department);
        $em->flush();

        return $this->redirectToRoute('admin_department_browse');
    }
}
