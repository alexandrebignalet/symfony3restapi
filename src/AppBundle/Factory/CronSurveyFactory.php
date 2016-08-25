<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:58
 */

namespace AppBundle\Factory;


use AppBundle\DTO\CronSurveyDTO;
use AppBundle\Entity\CronSurvey;

class CronSurveyFactory implements CronSurveyFactoryInterface
{
    /**
     * @param \AppBundle\Entity\Survey $survey
     * @param \DateTime $date
     * @param \DateTime $hourMin
     * @param \DateTime $hourMax
     * @param bool $everyday
     * @return CronSurvey
     * @internal param string $surveyTitle
     */
    public function create($survey, $date, $hourMin, $hourMax, $everyday)
    {
        return new CronSurvey($survey, $date, $hourMin, $hourMax, $everyday);
    }

    /**
     * @param CronSurveyDTO $surveyDTO
     * @return CronSurvey
     */
    public function createFromDTO(CronSurveyDTO $surveyDTO){

        $survey = self::create($surveyDTO->getSurvey(), $surveyDTO->getDate(), $surveyDTO->getHourMin(), $surveyDTO->getHourMax(), $surveyDTO->getEveryday());

        return $survey;
    }
}