<?php

namespace App\Form\Filter;

use App\Entity\Aircraft;
use App\Entity\DocumentCategory;
use App\Entity\Menu;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DocumentFilter extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    )
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documentCategory', EntityType::class, [
                'label' => 'documentCategory.label',
                'required' => false,
                'placeholder' => 'documentCategory.label',
                'class' => DocumentCategory::class,
                'query_builder' => function(EntityRepository $menuRepository) {
                    $dql = $menuRepository->createQueryBuilder('documentCategory');
                    $dql->orderBy('documentCategory.title', 'ASC');

                    return $dql;
                },
                'choice_label' => 'title'
            ])
            ->add('aircraft', EntityType::class, [
                'label' => 'aircraft.label',
                'required' => false,
                'placeholder' => 'aircraft.label',
                'class' => Aircraft::class,
                'query_builder' => function(EntityRepository $menuRepository) {
                    $dql = $menuRepository->createQueryBuilder('aircraft');
                    $dql->orderBy('aircraft.competitionNumber', 'ASC');

                    return $dql;
                },
                'choice_label' => function (Aircraft $aircraft) {
                    $type = $this->translator->trans('aircraft.type.'.$aircraft->getType());

                    return $aircraft->getCompetitionNumber().' - '.$aircraft->getLicense().' ('.$type.')';
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
