<?php

namespace AppBundle\Form\Type;

use AppBundle\Factory\SurveyFactoryInterface;
use AppBundle\Form\Transformer\EntityToIdObjectTransformer;
use AppBundle\Form\Transformer\ManyEntityToIdObjectTransformer;
use AppBundle\Repository\AnswerRepositoryInterface;
use AppBundle\Repository\AnswerResultRepositoryInterface;
use AppBundle\Repository\QuestionRepositoryInterface;
use AppBundle\Repository\SurveyRepositoryInterface;
use AppBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyResultType extends AbstractType
{
    /**
     * @var AnswerResultRepositoryInterface
     */
    private $answerResultRepository;

    /**
     * @var SurveyRepositoryInterface
     */
    private $surveyRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository, SurveyRepositoryInterface $surveyRepository, AnswerResultRepositoryInterface $answerResultRepository)
    {
        $this->answerResultRepository = $answerResultRepository;
        $this->surveyRepository = $surveyRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userTransformer = new EntityToIdObjectTransformer($this->userRepository);

        $surveyTransformer = new EntityToIdObjectTransformer($this->surveyRepository);


        $builder
            ->add(
                $builder->create('user', TextType::class)->addModelTransformer($userTransformer)
            )
            ->add(
                $builder->create('survey', TextType::class)->addModelTransformer($surveyTransformer)
            )
            ->add(
                $builder->create('answer_result', TextType::class), CollectionType::class, array(
                    'allow_add'     =>  true,
                    'by_reference'  =>  false
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\DTO\SurveyResultDTO',
        ]);
    }

    public function getName()
    {
        return 'survey_result';
    }
}