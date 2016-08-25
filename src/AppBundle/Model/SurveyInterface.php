<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:46
 */

namespace AppBundle\Model;


interface SurveyInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions();

    /**
     * @param array $questions
     * @return SurveyInterface
     */
    public function addQuestions(array $questions);

    /**
     * @param QuestionInterface $question
     * @return SurveyInterface
     */
    public function addQuestion(QuestionInterface $question);

    /**
     * @param QuestionInterface $question
     * @return SurveyInterface
     */
    public function removeQuestion(QuestionInterface $question);

    /**
     * @return SurveyInterface
     */
    public function removeAllQuestions();

    /**
     * @param QuestionInterface $question
     * @return boolean
     */
    public function hasQuestion(QuestionInterface $question);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function changeTitle($title);
}