<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/08/2016
 * Time: 10:20
 */

namespace AppBundle\Model;


use DateTime;

interface SurveyResultInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user);

    /**
     * @return SurveyInterface
     */
    public function getSurvey();

    /**
     * @param SurveyInterface $survey
     */
    public function setSurvey(SurveyInterface $survey);

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswerResults();

    /**
     * @param array $answerResults
     * @return AnswerInterface
     */
    public function addAnswerResults(array $answerResults);

    /**
     * @param AnswerResultInterface $answerResult
     * @return SurveyResultInterface
     */
    public function addAnswerResult(AnswerResultInterface $answerResult);

    /**
     * @param AnswerResultInterface $answerResult
     * @return SurveyResultInterface
     */
    public function removeAnswerResult(AnswerResultInterface $answerResult);

    /**
     * @return SurveyResultInterface
     */
    public function removeAllAnswerResults();

    /**
     * @return datetime
     */
    public function getDate();

    /**
     * @param datetime $date
     */
    public function setDate($date);

    /**
     * @return boolean
     */
    public function isCompleted();

    /**
     * @param boolean $completed
     */
    public function setCompleted($completed);
}