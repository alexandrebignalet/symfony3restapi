<?php

namespace AppBundle\DataTransformer;

use AppBundle\DTO\QuestionDTO;
use AppBundle\Model\QuestionInterface;

class QuestionDataTransformer
{
    public function convertToDTO(QuestionInterface $question)
    {
        $dto = new QuestionDTO();

        $dto->setEntitled($question->getEntitled());

        $dto->setAnswers($question->getAnswers());

        return $dto;
    }

    public function updateFromDTO(QuestionInterface $question, QuestionDTO $dto)
    {
        if ($question->getEntitled() !== $dto->getEntitled()) {
            $question->changeEntitled($dto->getEntitled());
        }

        $question->removeAllAnswers();

        foreach ($dto->getAnswers() as $answer) {
            $question->addAnswer($answer);
        }

        return $question;
    }
}