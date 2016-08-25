<?php

namespace AppBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionDTO implements DTOInterface, SymfonyFormDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $entitled;

    /**
     * @var array
     */
    private $answers;


    /**
     * QuestionDTO constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
            'entitled' => $this->entitled
        ];
    }


    /**
     * @return mixed
     */
    public function getEntitled()
    {
        return $this->entitled;
    }

    /**
     * @param mixed $entitled
     * @return $this
     */
    public function setEntitled($entitled)
    {
        $this->entitled = $entitled;
        return $this;
    }

    /**
     * @return ArrayCollection<AnswerInterface>
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Array<AnswerInterface> $answers
     * @return $this
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
        return $this;
    }

}