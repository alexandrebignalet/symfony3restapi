<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 20/08/2016
 * Time: 14:56
 */

namespace AppBundle\Model;


interface AnswerResultInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return AnswerInterface
     */
    public function getAnswer();

    /**
     * @param QuestionInterface $question
     */
    public function setQuestion(QuestionInterface $question);

    /**
     * @return QuestionInterface
     */
    public function getQuestion();

    /**
     * @param AnswerInterface $answer
     */
    public function setAnswer(AnswerInterface $answer);
}