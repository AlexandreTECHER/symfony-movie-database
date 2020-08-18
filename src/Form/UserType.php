<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ]
            ])
            // On précise que le champs password est un RepeatedType
            // C'est ce type de champs qui permet d'avoir deux champs password où le deuxième confirme le premier
            // Cette méthode est toujours très utilisée sur le web mais elle tend à disparaitre pour plusieurs raisons
            // Le RepeatedType va automatiquement produire une contrainte de validation sur les deux champs pour s'assurer qu'ils ont la même valeur
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux champs doivent avoir la même valeur',
                // On précise que le champs n'est pas requis pour éviter de devoir remplir les deux champs à chaque modification
                'required' => false,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                // On doit préciser que ce champs n'est pas mappé avec l'entité
                // pour éviter que la valeur entrée ne soit affectée à notre User
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
