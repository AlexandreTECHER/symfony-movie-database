<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\CrewMember;
use App\Entity\Director;
use App\Entity\Employment;
use App\Entity\Genre;
use App\Entity\User;
use App\Form\GenreType;
use App\Form\RegistrationFormType;
use App\Service\Slugger;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    /**
     * @Route("/demo", name="demo")
     */
    public function addGenre(Request $request)
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($genre);
            $em->flush();

            return $this->redirectToRoute('demo');
        }


        return $this->render('demo/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/demo/{id}", name="demo_edit", requirements={"id": "\d+"})
     */
    public function editGenre(Request $request, Genre $genre)
    {
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('demo');
        }


        return $this->render('demo/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/demo/form", name="demo_in_controller")
     */
    public function formInController(Request $request)
    {
        $form = $this->createFormBuilder()
                ->add('name', TextType::class)
                ->add('messageATous', TextareaType::class, [
                    'label' => 'Le message à envoyer'
                ])
                ->add('color', ColorType::class)
                ->getForm()
                ;
        
        return $this->render('demo/form_in_controller.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }

    /**
     * Démo sur l'utilisation des Entités avec héritage
     * 
     * @Route("/demo/employment/{id}", name="demo_doctrine_inheritance")
     */
    public function showEmployment(Employment $employment)
    {
        if ($employment instanceof Director) {
            dd('Il s\'agit d\'un réalisateur');
        }

        if ($employment instanceof Actor) {
            dd('Il s\'agit d\'un acteur');
        }

        if ($employment instanceof CrewMember) {
            dd('Il s\'agit d\'une perosnne importante dont tout le monde se fiche bien du nom en lisant le générique');
        }

        dd($employment);
    }

    /**
     * Démo sur l'utilisation des Entités avec héritage
     * 
     * @Route("/demo/actor/{id}", name="demo_doctrine_inheritance_single")
     */
    public function showActor(Actor $actor)
    {
        dd($actor);
    }

    /**
     * Pour injecter un service dans la méthode du contrôleur,
     * on ajoute le service dans les paramètres de la fonction
     * On laisse le ParamConverter créer l'objet de la classe Slugger
     * 
     * @Route("/demo/service")
     */
    public function demoService(Slugger $slugger)
    {
        $slugger->slugifyAllMovies();

        // dd($slugger->slugify('Le groupe Casino a signé un accord avec Aldi France en vue de la cession de magasins et d’entrepôts Leader Price en France métropolitaine, pour 735 millions d’euros '));

        // On redirige vers la page principale à défaut de mieux
        // Je rappelel qu'on est juste sur une route de démonstration donc cette redirection n'a pas de sens
        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/demo/user/{id}/edit", name="demo_user_edit")
     */
    public function demoUserEdit(User $user)
    {
        $form = $this->createForm(RegistrationFormType::class, $user);

        return $this->render('demo/user_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /** Demo sur la connexion en API
     * @Route("/demo/api", name="demi_connexion_api")
     */
    public function demoConnexion()
    {
        dd($this->getUser());
    }
}
