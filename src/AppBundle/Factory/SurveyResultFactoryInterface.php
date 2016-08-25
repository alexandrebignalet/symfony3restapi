<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 17/08/2016
 * Time: 11:56
 */

namespace AppBundle\Factory;

use AppBundle\DTO\SurveyResultDTO;
use AppBundle\Entity\SurveyResult;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\UserInterface;
use DateTime;

interface SurveyResultFactoryInterface
{
    /**
     * @param   UserInterface $user
     * @param   SurveyInterface $survey
     * @return  SurveyResult
     */
    public function create(UserInterface $user, SurveyInterface $survey);

    /**
     * @param SurveyResultDTO $cronSurveyDTO
     * @return mixed
     */
    public function createFromDTO(SurveyResultDTO $cronSurveyDTO);
}