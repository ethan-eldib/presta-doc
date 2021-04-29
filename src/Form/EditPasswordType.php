<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'disabled' => true
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'disabled' => true,
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe doivent être identique',
                'label' => 'Votre mot de passe',
                'required' => true,
                'constraints' => array(new Regex(
                    array(
                        'pattern' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$#',
                        'message' => 'Le nouveau mot de passe doit comporter au moins 6 caractères et inclure au moins une lettre majuscule et un chiffre'
                    )
                )),
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Au moins 6 caractères, 1 lettre majuscule et 1 chiffre...'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
