<?php

namespace AppBundle\DTO;

use AppBundle\Model\SurveyInterface;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;


class CronSurveyDTO implements DTOInterface, SymfonyFormDTO
{
    /**
     * @var datetime
     */
    private $date;

    /**
     * @var datetime
     * @Assert\NotBlank()
     */
    private $hourMin;

    /**
     * @var datetime
     * @Assert\NotBlank()
     */
    private $hourMax;

    /**
     * @var boolean
     */
    private $everyday;

    /**
     * @var SurveyInterface
     * @Assert\NotBlank()
     */
    private $survey;


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
     * @return mixed
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param SurveyInterface $survey
     */
    public function setSurvey(SurveyInterface $survey)
    {
        $this->survey = $survey;
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
     * @return datetime
     */
    public function getHourMin()
    {
        return $this->hourMin;
    }

    /**
     * @param datetime $hourMin
     */
    public function setHourMin($hourMin)
    {
        $this->hourMin = $hourMin;
    }

    /**
     * @return datetime
     */
    public function getHourMax()
    {
        return $this->hourMax;
    }

    /**
     * @param datetime $hourMax
     */
    public function setHourMax($hourMax)
    {
        $this->hourMax = $hourMax;
    }

    /**
     * @return boolean
     */
    public function getEveryday()
    {
        return $this->everyday;
    }

    /**
     * @param boolean $everyday
     */
    public function setEveryday($everyday)
    {
        $this->everyday = $everyday;
    }

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return [
            'survey'    => $this->survey,
            'date'      => $this->date,
            'hourMin'   => $this->hourMin,
            'hourMax'   => $this->hourMax,
            'everyday'  => $this->everyday
        ];
    }
}