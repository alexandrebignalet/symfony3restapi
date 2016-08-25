<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Transformer\EntityToIdObjectTransformer;
use AppBundle\Form\Transformer\ManyEntityToIdObjectTransformer;
use AppBundle\Repository\AnswerRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;

    /**
     * QuestionType constructor.
     * @param AnswerRepositoryInterface $answerRepository
     */
    public function __construct(AnswerRepositoryInterface $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entitled', TextType::class)
        ;

        $answerTransformer = new EntityToIdObjectTransformer($this->answerRepository);
        $answerCollectionTransformer = new ManyEntityToIdObjectTransformer($answerTransformer);

        $builder
            ->add(
                $builder->create('answers', TextType::class)->addModelTransformer($answerCollectionTransformer)
            )
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\DTO\QuestionDTO',
        ]);
    }

    public function getName()
    {
        return 'question';
    }
}