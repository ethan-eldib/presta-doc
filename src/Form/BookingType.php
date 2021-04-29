<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                DateType::class,
                $this->getConfiguration("Quel jour souhaitez-vous être appelé ?", false, [
                    "widget" => "single_text"
                ])
            )
            ->add(
                'startTime',
                TimeType::class,
                $this->getConfiguration("À quelle heure ?", false, [
                    "widget" => "single_text"
                ])
            )
            // ->add(
            //     'endTime',
            //     TimeType::class,
            //     $this->getConfiguration("À quelle heure ?", false, [
            //         "widget" => "single_text",
            //     ])
            // )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
