<?php

namespace AppBundle\DTO;

use AppBundle\Model\SurveyInterface;
use AppBundle\Model\UserInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as DTOAssert;

/**
 * Class SurveyResultDTO
 * @package AppBundle\DTO
 * @DTOAssert\AnswerNumberEqualQuestionsNumber()
 */
class SurveyResultDTO implements DTOInterface, SymfonyFormDTO
{
    /**
     * @var datetime
     */
    private $date;

    /**
     * @var boolean
     */
    private $completed;

    /**
     * @var SurveyInterface
     * @Assert\NotBlank()
     */
    private $survey;

    /**
     * @var UserInterface
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @var array
     * @Assert\Count(min="1", minMessage="This SurveyResult needs to be associated with at least one AnswerResult ID")
     */
    private $answerResults;


    public function __construct()
    {
        $this->answerResults = new ArrayCollection();
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
            'date'      => $this->date,
            'completed' => $this->completed,
            'survey'    => $this->survey,
            'user'      => $this->user,
            'answers'   => $this->answerResults,
        ];
    }


    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return SurveyInterface
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param SurveyInterface $survey
     * @return $this
     */
    public function setSurvey(SurveyInterface $survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return ArrayCollection<AnswerResultInterface>
     */
    public function getAnswerResult()
    {
        return $this->answerResults;
    }

    /**
     * @param Array<AnswerResultInterface> $answers
     * @return $this
     */
    public function setAnswerResult($answerResult)
    {
        $this->answerResults = $answerResult;

        return $this;
    }

    /**
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return boolean
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param boolean $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }
}