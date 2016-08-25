<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:58
 */

namespace AppBundle\Factory;


use AppBundle\DTO\AnswerResultDTO;
use AppBundle\Entity\AnswerResult;
use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\UserInterface;

class AnswerResultFactory implements AnswerResultFactoryInterface
{
    /**
     * @param QuestionInterface $question
     * @param AnswerInterface $answer
     * @return AnswerResult
     */
    public function create(QuestionInterface $question, AnswerInterface $answer)
    {
        return new AnswerResult($question, $answer);
    }

    /**
     * @param  AnswerResultDTO   $answerResultDTO
     * @return AnswerResult
     */
    public function createFromDTO(AnswerResultDTO $answerResultDTO){

        $surveyResult = self::create($answerResultDTO->getQuestion(), $answerResultDTO->getAnswer());

        return $surveyResult;
    }
}