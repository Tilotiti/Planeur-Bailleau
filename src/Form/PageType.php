<?php

namespace App\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Menu;
use App\Entity\Page;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('menu', EntityType::class, [
                'label' => 'menu.label',
                'required' => false,
                'placeholder' => 'menu.any',
                'class' => Menu::class,
                'query_builder' => function(EntityRepository $menuRepository) {
                    $dql = $menuRepository->createQueryBuilder('menu');
                    $dql->orderBy('menu.order', 'ASC');

                    return $dql;
                }
            ])
            ->add('code', TextType::class, [
                'required' => false,
                'label' => 'page.code.label',
                'help' => 'page.code.help',
                'help_html' => true
            ])
            ->add('translations', TranslationsType::class, [
                'fields' => [
                    'title' => [
                        'field_type' => TextType::class,
                        'label' => 'page.title.label'
                    ],
                    'url' => [
                        'field_type' => TextType::class,
                        'label' => 'page.url.label'
                    ],
                    'content' => [
                        'field_type' => TextareaType::class,
                        'label' => 'page.content.label',
                        'attr' => [
                            'class' => 'wysiwyg'
                        ]
                    ],
                ]
            ])
            ->add('order', NumberType::class, [
                'label' => 'menu.order.title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
