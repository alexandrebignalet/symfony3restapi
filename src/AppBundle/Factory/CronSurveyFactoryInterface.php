<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 17/08/2016
 * Time: 11:56
 */

namespace AppBundle\Factory;

use AppBundle\DTO\CronSurveyDTO;
use AppBundle\Entity\CronSurvey;
use AppBundle\Entity\Survey;
use DateTime;

interface CronSurveyFactoryInterface
{
    /**
     * @param   Survey      $survey
     * @param   datetime    $date
     * @param   datetime    $hourMin
     * @param   datetime    $hourMax
     * @param   bool        $everyday
     * @return  CronSurvey
     */
    public function create($survey, $date, $hourMin, $hourMax, $everyday);

    /**
     * @param CronSurveyDTO $cronSurveyDTO
     * @return mixed
     */
    public function createFromDTO(CronSurveyDTO $cronSurveyDTO);
}