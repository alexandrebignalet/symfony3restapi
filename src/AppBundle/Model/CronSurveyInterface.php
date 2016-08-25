<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 17/08/2016
 * Time: 14:09
 */

namespace AppBundle\Model;


use DateTime;

interface CronSurveyInterface
{
    /**
     * @param SurveyInterface $survey
     * @return boolean
     */
    public function isSurveyEligible(SurveyInterface $survey);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getSurvey();

    /**
     * @param SurveyInterface $survey
     */
    public function setSurvey(SurveyInterface $survey);

    /**
     * @return Datetime
     */
    public function getDate();

    /**
     * @param Datetime $date
     */
    public function setDate($date);

    /**
     * @return Datetime
     */
    public function getHourMin();

    /**
     * @param Datetime $hourMin
     */
    public function setHourMin($hourMin);

    /**
     * @return Datetime
     */
    public function getHourMax();

    /**
     * @param Datetime $hourMax
     */
    public function setHourMax($hourMax);

    /**
     * @return boolean
     */
    public function getEveryday();

    /**
     * @param boolean $everyday
     */
    public function setEveryday($everyday);
}