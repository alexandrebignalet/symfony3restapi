<?php

namespace AppBundle\DataTransformer;

use AppBundle\DTO\SurveyDTO;
use AppBundle\DTO\SurveyResultDTO;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\SurveyResultInterface;

class SurveyResultDataTransformer
{
    public function convertToDTO(SurveyResultInterface $surveyResult)
    {
        $dto = new SurveyResultDTO();

        $dto->setUser($surveyResult->getUser());

        $dto->setSurvey($surveyResult->getSurvey());

        $dto->setDate($surveyResult->getDate());

        $dto->setCompleted($surveyResult->isCompleted());

        $dto->setAnswerResults($surveyResult->getAnswerResults());

        return $dto;
    }

    public function updateFromDTO(SurveyResultInterface $surveyResult, SurveyResultDTO $dto)
    {
        if ($surveyResult->isCompleted() !== $dto->getCompleted()) {
            $surveyResult->setCompleted($dto->getCompleted());
        }

        if ($surveyResult->getSurvey() !== $dto->getSurvey()) {
            $surveyResult->setSurvey($dto->getSurvey());
        }

        if ($surveyResult->getUser() !== $dto->getUser()) {
            $surveyResult->setUser($dto->getUser());
        }

        if ($surveyResult->getDate() !== $dto->getDate()) {
            $surveyResult->setDate($dto->getDate());
        }

        $surveyResult->removeAllAnswerResults();

        foreach ($dto->getAnswerResults() as $answerResult) {
            $surveyResult->addAnswerResult($answerResult);
        }

        return $surveyResult;
    }
}