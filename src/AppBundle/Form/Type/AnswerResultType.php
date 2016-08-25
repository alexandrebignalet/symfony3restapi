<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Transformer\EntityToIdObjectTransformer;
use AppBundle\Repository\AnswerRepositoryInterface;
use AppBundle\Repository\QuestionRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerResultType extends AbstractType
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;

    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;


    public function __construct(QuestionRepositoryInterface $questionRepository, AnswerRepositoryInterface $answerRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questionTransformer = new EntityToIdObjectTransformer($this->questionRepository);

        $answerTransformer = new EntityToIdObjectTransformer($this->answerRepository);



        $builder
            ->add(
                $builder->create('question', TextType::class)->addModelTransformer($questionTransformer)
            )
            ->add(
                $builder->create('answer', TextType::class)->addModelTransformer($answerTransformer)
            )
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\DTO\AnswerResultDTO',
        ]);
    }

    public function getName()
    {
        return 'answer_result';
    }
}