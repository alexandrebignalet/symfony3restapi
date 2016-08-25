<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Transformer\EntityToIdObjectTransformer;
use AppBundle\Repository\SurveyRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CronSurveyType extends AbstractType
{
    /**
     * @var SurveyRepositoryInterface
     */
    private $surveyRepository;

    public function __construct(SurveyRepositoryInterface $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Datetime format to send for hourMin and hourMax is like "2011-06-05 12:15:00"
     * date format is "2016-08-17"
     * Everyday should be true or false ¡¡not in string!!
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => "yyyy-MM-dd",
            ))
            ->add('hourMin', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => "yyyy-MM-dd HH:mm:ss",
            ))
            ->add('hourMax', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => "yyyy-MM-dd HH:mm:ss",
            ))
            ->add('everyday', CheckboxType::class)
        ;

        $surveyTransformer = new EntityToIdObjectTransformer($this->surveyRepository);

        $builder
            ->add(
                $builder->create('survey', TextType::class)->addModelTransformer($surveyTransformer)
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\DTO\CronSurveyDTO',
        ]);
    }

    public function getName()
    {
        return 'cron_survey';
    }
}