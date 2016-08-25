<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Transformer\EntityToIdObjectTransformer;
use AppBundle\Form\Transformer\ManyEntityToIdObjectTransformer;
use AppBundle\Repository\QuestionRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyType extends AbstractType
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
        ;

        $questionTransformer = new EntityToIdObjectTransformer($this->questionRepository);
        $questionCollectionTransformer = new ManyEntityToIdObjectTransformer($questionTransformer);

        $builder
            ->add(
                $builder->create('questions', TextType::class)->addModelTransformer($questionCollectionTransformer)
            )
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\DTO\SurveyDTO',
        ]);
    }

    public function getName()
    {
        return 'survey';
    }
}