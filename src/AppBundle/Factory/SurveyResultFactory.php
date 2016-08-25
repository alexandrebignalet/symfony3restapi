<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:58
 */

namespace AppBundle\Factory;


use AppBundle\DTO\SurveyResultDTO;
use AppBundle\Entity\AnswerResult;
use AppBundle\Entity\SurveyResult;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\QuestionRepositoryInterface;
use AppBundle\Repository\AnswerRepositoryInterface;
use DateTime;

class SurveyResultFactory implements SurveyResultFactoryInterface
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
     * @param   UserInterface $user
     * @param   SurveyInterface $survey
     * @return  SurveyResult
     */
    public function create(UserInterface $user, SurveyInterface $survey)
    {
        return new SurveyResult($user, $survey);
    }

    /**
     * @param  SurveyResultDTO   $surveyResultDTO
     * @return SurveyResult
     */
    public function createFromDTO(SurveyResultDTO $surveyResultDTO){

        $surveyResult = self::create($surveyResultDTO->getUser(), $surveyResultDTO->getSurvey());

        foreach ($surveyResultDTO->getAnswerResult() as $answerResult){

            $question = $this->questionRepository->findOneById($answerResult['question']);
            $answer = $this->answerRepository->findOneById($answerResult['answer']);

            $answerResult = new AnswerResult($question, $answer);

            $surveyResult->addAnswerResult($answerResult);
        }

        return $surveyResult;
    }
}