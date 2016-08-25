<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:58
 */

namespace AppBundle\Factory;


use AppBundle\DTO\AnswerDTO;
use AppBundle\Entity\Answer;
use AppBundle\Model\QuestionInterface;

class AnswerFactory implements AnswerFactoryInterface
{
    /**
     * @param  string $answerEntitled
     * @param  int $answerValue
     * @return Answer
     */
    public function create($answerEntitled, $answerValue)
    {
        return new Answer($answerEntitled, $answerValue);
    }

    /**
     * @param  AnswerDTO   $answerDTO
     * @return Answer
     */
    public function createFromDTO(AnswerDTO $answerDTO){

        $answer = self::create($answerDTO->getEntitled(), $answerDTO->getValue());

        return $answer;
    }
}