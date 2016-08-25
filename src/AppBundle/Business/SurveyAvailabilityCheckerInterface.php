<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/08/2016
 * Time: 18:08
 */

namespace AppBundle\Business;

use AppBundle\Model\SurveyInterface;

interface SurveyAvailabilityCheckerInterface
{
    /**
     * @param array $surveys
     * @return mixed
     * Allow User to access or not a Survey
     */
    public function getSurveysAvailable(array $surveys);

    /**
     * @param SurveyInterface $survey
     * @return mixed
     * Allow User to access or not a Survey
     */
    public function isAvailable(SurveyInterface $survey);
}