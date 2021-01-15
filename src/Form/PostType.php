<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'post.title.label'
            ])
            ->add('code', TextType::class, [
                'label' => 'post.code.label',
                'required' => false
            ])
            ->add('locale', ChoiceType::class, [
                'label' => 'post.locale.label',
                'choices' => [
                    'post.locale.fr' => 'fr',
                    'post.locale.en' => 'en'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'post.content.label',
                'attr' => [
                    'class' => 'wysiwyg'
                ]
            ])
            ->add('draft', ChoiceType::class, [
                'label' => 'post.draft.label',
                'choices' => [
                    'post.draft.true' => true,
                    'post.draft.false' => false
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
