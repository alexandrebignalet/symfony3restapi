<?php

namespace AppBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


class SurveyDTO implements DTOInterface, SymfonyFormDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var array
     * @Assert\Count(min="1", minMessage="This Survey needs to be associated with at least one Question ID")
     */
    private $questions;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
            'title' => $this->title
        ];
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return ArrayCollection<QuestionInterface>
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param Array<QuestionInterface> $questions
     * @return $this
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

}