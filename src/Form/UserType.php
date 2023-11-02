<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
        ])
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Membre' => 'ROLE_USER',
                'Manager' => 'ROLE_MANAGER',
                'Administrateur' => 'ROLE_ADMIN',
            ],
            'multiple' => true,
            'expanded' => true,
            'label' => 'Rôle(s)',
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
        ])
        ->add('password', TextType::class, [
            'help' => 'Make sure it\'s at least 8 characters including a number and a lowercase letter and a special character.',
        ])
        ->add('Firstname', TextType::class, [
            'label' => 'Prénom',
        ])
        ->add('Lastname', TextType::class, [
            'label' => 'Nom de famille',
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
