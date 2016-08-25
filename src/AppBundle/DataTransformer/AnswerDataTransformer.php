<?php

namespace AppBundle\DataTransformer;

use AppBundle\DTO\AnswerDTO;
use AppBundle\Model\AnswerInterface;

class AnswerDataTransformer
{
    public function convertToDTO(AnswerInterface $answer)
    {
        $dto = new AnswerDTO();

        $dto->setEntitled($answer->getEntitled());

        $dto->setValue($answer->getValue());

        return $dto;
    }

    public function updateFromDTO(AnswerInterface $answer, AnswerDTO $dto)
    {
        if ($answer->getEntitled() !== $dto->getEntitled()) {
            $answer->changeEntitled($dto->getEntitled());
        }

        $answer->setValue($dto->getValue());

        return $answer;
    }
}