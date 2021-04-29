<?php

namespace App\Form;

use App\Entity\Folders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class FoldersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Nom du dossier",
                'attr' => [
                    'placeholder' => "Choisissez un nom pour votre dossier (obligatoire)"
                ]
            ])
            // Ajout du champs "folders" dans le formulaire
            // Ce champ n'est pas lié à la BDD
            ->add('folders', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                   new All([
                       'constraints' => [
                        new File([
                            'maxSize' => '5Mi',
                            'maxSizeMessage' => 'Limite de 5Mo dépassée',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ],
                            'mimeTypesMessage' => 'Merci de télecharger un fichier au format PDF, WORD ou EXCEL'
                        ])
                       ]
                   ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Folders::class,
        ]);
    }
}
