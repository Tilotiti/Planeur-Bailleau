<?php

namespace App\Form;

use App\Entity\Aircraft;
use App\Entity\Document;
use App\Entity\DocumentCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DocumentType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
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
            ->add('title', TextType::class, [
                'label' => 'post.title.label'
            ])
            ->add('aircraft', EntityType::class, [
                'label' => 'aircraft.label',
                'placeholder' => 'aircraft.none',
                'required' => false,
                'class' => Aircraft::class,
                'query_builder' => function(EntityRepository $entityRepository) {
                    $dql = $entityRepository->createQueryBuilder('aircraft');
                    $dql->orderBy('aircraft.competitionNumber', 'ASC');

                    return $dql;
                },
                'choice_label' => function (Aircraft $aircraft) {
                    $type = $this->translator->trans('aircraft.type.'.$aircraft->getType());

                    return $aircraft->getCompetitionNumber().' - '.$aircraft->getModel().' - '.$aircraft->getLicense().' ('.$type.')';
                }
            ])
            ->add('documentCategory', EntityType::class, [
                'label' => 'documentCategory.label',
                'placeholder' => 'documentCategory.none',
                'required' => false,
                'class' => DocumentCategory::class,
                'query_builder' => function(EntityRepository $entityRepository) {
                    $dql = $entityRepository->createQueryBuilder('documentCategory');
                    $dql->orderBy('documentCategory.title', 'ASC');

                    return $dql;
                }
            ])
            ->add('file', FileType::class, [
                'label' => 'document.file.label',
                'required' => $options['new']
            ])
            ->add('url', UrlType::class, [
                'label' => 'document.url.label',
                'disabled' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'new' => false
        ]);
    }
}
