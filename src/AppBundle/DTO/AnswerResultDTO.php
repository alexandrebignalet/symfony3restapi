<?php

namespace AppBundle\DTO;

use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


class AnswerResultDTO implements DTOInterface, SymfonyFormDTO
{
    /**
     * @var QuestionInterface
     * @Assert\NotBlank()
     */
    private $question;

    /**
     * @var AnswerInterface
     * @Assert\NotBlank()
     */
    private $answer;


    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getDataClass()
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return [
            'question'   => $this->question,
            'answer'     => $this->answer
        ];
    }

    /**
     * @return AnswerInterface
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param AnswerInterface $answer
     * @return $this
     */
    public function setAnswer(AnswerInterface $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return QuestionInterface
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param QuestionInterface $question
     * @return $this
     */
    public function setQuestion(QuestionInterface $question)
    {
        $this->question = $question;

        return $this;
    }
}