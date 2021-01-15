<?php

namespace App\Form;

use App\Entity\Aircraft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AircraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('license', TextType::class, [
                'label' => 'aircraft.license.label'
            ])
            ->add('competitionNumber', TextType::class, [
                'label' => 'aircraft.competitionNumber.label'
            ])
            ->add('model', TextType::class, [
                'label' => 'aircraft.model.label'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'aircraft.type.label',
                'choices' => [
                    'aircraft.type.'.Aircraft::TYPE_MONO => Aircraft::TYPE_MONO,
                    'aircraft.type.'.Aircraft::TYPE_BI => Aircraft::TYPE_BI,
                    'aircraft.type.'.Aircraft::TYPE_TOWING => Aircraft::TYPE_TOWING,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Aircraft::class,
        ]);
    }
}
