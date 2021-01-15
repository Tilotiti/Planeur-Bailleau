<?php

namespace App\Form\Filter;

use App\Entity\Menu;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageFilter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('menu', EntityType::class, [
                'label' => 'menu.label',
                'required' => false,
                'placeholder' => 'menu.label',
                'class' => Menu::class,
                'query_builder' => function(EntityRepository $menuRepository) {
                    $dql = $menuRepository->createQueryBuilder('menu');
                    $dql->orderBy('menu.order', 'ASC');

                    return $dql;
                }
            ])
            ->add('title', TextType::class, [
                'label' => 'search.label',
                'required' => false,
                'attr' => [
                    'placeholder' => 'search.placeholder'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
