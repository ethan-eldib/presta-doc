<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => "Nom",
                    'attr' => [
                        'placeholder' => "Entrez votre nom...",
                    ]
                ]
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => "Prénom",
                    'attr' => [
                        'placeholder' => "Entrez votre prénom..."
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => "Email",
                    'attr' => [
                        'placeholder' => "Entrez votre adresse email..."
                    ]
                ]
            )
            ->add(
                'hash',
                PasswordType::class,
                [
                    'label' => "Mot de passe",
                    'attr' => [
                        'placeholder' => "Au moins 6 caractères, 1 lettre majuscule et 1 chiffre..."
                    ]
                ]
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                [
                    'label' => "Confirmation de votre mot de passe",
                    'attr' => [
                        'placeholder' => "Confirmer le mot de passe..."
                    ]

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
