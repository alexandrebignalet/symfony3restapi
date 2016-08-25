<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 16:18
 */

namespace AppBundle\Model;


use Doctrine\Common\Collections\Collection;

interface QuestionInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getAnswers();

    /**
     * @param array $answers
     * @return QuestionInterface
     */
    public function addAnswers(array $answers);

    /**
     * @param AnswerInterface $answer
     * @return QuestionInterface
     */
    public function addAnswer(AnswerInterface $answer);

    /**
     * @param AnswerInterface $answer
     * @return QuestionInterface
     */
    public function removeAnswer(AnswerInterface $answer);

    /**
     * @param AnswerInterface $answer
     * @return boolean
     */
    public function hasAnswer(AnswerInterface $answer);

    /**
     * @return QuestionInterface
     */
    public function removeAllAnswers();

    /**
     * @return string
     */
    public function getEntitled();

    /**
     * @param string $entitled
     */
    public function changeEntitled($entitled);
}