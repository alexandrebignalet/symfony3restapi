<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:58
 */

namespace AppBundle\Factory;


use AppBundle\DTO\QuestionDTO;
use AppBundle\Entity\Question;

class QuestionFactory implements QuestionFactoryInterface
{
    /**
     * @param  string       $questionEntitled
     * @return Question
     */
    public function create($questionEntitled)
    {
        return new Question($questionEntitled);
    }

    /**
     * @param  QuestionDTO   $questionDTO
     * @return Question
     */
    public function createFromDTO(QuestionDTO $questionDTO){

        $question = self::create($questionDTO->getEntitled());

        foreach ($questionDTO->getAnswers() as $answer) { /** @var $answer \AppBundle\Model\AnswerInterface */
            $question->addAnswer($answer);
        }

        return $question;
    }
}