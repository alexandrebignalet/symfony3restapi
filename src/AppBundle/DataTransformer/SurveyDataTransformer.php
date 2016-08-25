<?php

namespace AppBundle\DataTransformer;

use AppBundle\DTO\SurveyDTO;
use AppBundle\Model\SurveyInterface;

class SurveyDataTransformer
{
    public function convertToDTO(SurveyInterface $survey)
    {
        $dto = new SurveyDTO();

        $dto->setTitle($survey->getTitle());

        $dto->setQuestions($survey->getQuestions());

        return $dto;
    }

    public function updateFromDTO(SurveyInterface $survey, SurveyDTO $dto)
    {
        if ($survey->getTitle() !== $dto->getTitle()) {
            $survey->changeTitle($dto->getTitle());
        }

        $survey->removeAllQuestions();

        foreach ($dto->getQuestions() as $question) {
            $survey->addQuestion($question);
        }

        return $survey;
    }
}