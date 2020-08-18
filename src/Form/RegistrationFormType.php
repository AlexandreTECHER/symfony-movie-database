<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            // Cet event listener est une démo pour comparer le formulaire de base sur /register
            // avec un formulaire différent sur /demo/user/{id}/edit
            // C'est le même FormType mais les champs sont différents selon que l'utilisateur est neuf (pas d'id)
            // ou qu'il existe déjà en BDD
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // $data correspond à l'entité associée au formulaire
                $data = $event->getData();
                $form = $event->getForm();

                // $data vaut null si aucun objet n'est associé au formulaire
                // Sinon $data est l'objet de l'entité associée au formulaire

                // On pourrait se dire que si on appelle ce formulaire dans le cas d'un user existant
                // il n'est plus nécessaire de demander de confirmer d'accepter les termes
                // donc on enlève le champs !
                if ($data->getId() != null) {
                    $form->remove('agreeTerms');
                    $form->add('pseudo', null, [
                        'mapped' => false,
                        'required' => false,
                    ]);
                }

            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
