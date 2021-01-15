<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'user.email.label'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname.label'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname.label'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'user.role.label',
                'choices' => [
                    'user.role.'.User::ROLE_USER => User::ROLE_USER,
                    'user.role.'.User::ROLE_ADMIN => User::ROLE_ADMIN
                ],
                'expanded' => true,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
