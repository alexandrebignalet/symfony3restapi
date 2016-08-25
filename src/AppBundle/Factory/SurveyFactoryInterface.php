<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:53
 */

namespace AppBundle\Factory;


use AppBundle\DTO\SurveyDTO;
use AppBundle\Entity\Survey;

interface SurveyFactoryInterface
{
    /**
     * @param  string       $surveyTitle
     * @return Survey
     */
    public function create($surveyTitle);

    /**
     * @param  SurveyDTO    $surveyDTO
     * @return Survey
     */
    public function createFromDTO(SurveyDTO $surveyDTO);
}