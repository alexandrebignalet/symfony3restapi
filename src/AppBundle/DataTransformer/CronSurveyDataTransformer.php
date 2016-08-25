<?php

namespace AppBundle\DataTransformer;

use AppBundle\DTO\CronSurveyDTO;
use AppBundle\Model\CronSurveyInterface;

class CronSurveyDataTransformer
{
    public function convertToDTO(CronSurveyInterface $cronSurvey)
    {
        $dto = new CronSurveyDTO();

        $dto->setSurvey($cronSurvey->getSurvey());

        $dto->setDate($cronSurvey->getDate());

        $dto->setHourMin($cronSurvey->getHourMin());

        $dto->setHourMax($cronSurvey->getHourMax());

        $dto->setEveryday($cronSurvey->getEveryday());

        return $dto;
    }

    public function updateFromDTO(CronSurveyInterface $cronSurvey, CronSurveyDTO $dto)
    {
        $cronSurvey->setSurvey($dto->getSurvey());

        $cronSurvey->setDate($dto->getDate());

        $cronSurvey->setHourMax($dto->getHourMax());

        $cronSurvey->getHourMin($dto->getHourMin());

        $cronSurvey->setEveryday($dto->getEveryday());

        return $cronSurvey;
    }
}