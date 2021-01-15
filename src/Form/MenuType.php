<?php

namespace App\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class, [
                'fields' => [
                    'title' => [
                        'field_type' => TextType::class,
                        'label' => 'menu.title.label'
                    ],
                    'url' => [
                        'field_type' => TextType::class,
                        'label' => 'menu.url.label'
                    ],
                ]
            ])
            ->add('order', NumberType::class, [
                'label' => 'menu.order.title'
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'menu.public.title',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
