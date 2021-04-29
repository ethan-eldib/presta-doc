<?php

namespace App\Form;

use App\Form\FormExtension\HoneyPotType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends HoneyPotType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Saisir votre prénom...'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Saisir votre nom...'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Saisir votre n° de téléphone...'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email',
                'attr' => [
                    'placeholder' => 'Saisir votre adresse email...'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'placeholder' => 'En quoi puis-je vous aider ?'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
