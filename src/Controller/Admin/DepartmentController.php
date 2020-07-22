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
     * @Route("/{id}", name="read", requirements={"id" : "\d+"})
     */
    public function read(Department $department)
    {
        return $this->render('admin/department/read.html.twig', [
            'department' => $department,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     */
    public function edit(Department $department, Request $request)
    {

        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $department->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->flush();

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

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            return $this->redirectToRoute('admin_department_browse');
        }

        return $this->render('admin/department/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     */
    public function delete(Department $department)
    {

        $em = $this->getDoctrine()->getManager();

        $em->remove($department);
        $em->flush();

        return $this->redirectToRoute('admin_department_browse');

    }

}
