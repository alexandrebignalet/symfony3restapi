<?php

namespace AppBundle\Entity;


use AppBundle\Model\CronSurveyInterface;
use AppBundle\Model\SurveyInterface;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CronSurveyEntityRepository")
 * @ORM\Table(name="cron_survey")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class CronSurvey implements CronSurveyInterface, \JsonSerializable
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMSSerializer\Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Survey")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     * })
     * @JMSSerializer\Type("AppBundle\Entity\Survey")
     * @JMSSerializer\MaxDepth(2)
     * @JMSSerializer\Expose
     */
    private $survey;

    /**
     * @var datetime The date when the survey has to be proposed.
     * @ORM\Column(type="datetime", name="date", nullable=true)
     * @JMSSerializer\Expose
     */
    protected $date;

    /**
     * @var datetime The minimum hour when the survey has to be proposed.
     * @ORM\Column(type="datetime", name="hourMin")
     * @JMSSerializer\Expose
     */
    protected $hourMin;

    /**
     * @var datetime The maximum hour when the survey has to be proposed.
     * @ORM\Column(type="datetime", name="hourMax")
     * @JMSSerializer\Expose
     */
    protected $hourMax;

    /**
     * @var boolean If the survey has to be proposed everyday between hours min and max.
     * @ORM\Column(type="boolean", name="everyday")
     * @JMSSerializer\Expose
     */
    protected $everyday;


    /**
     * CronSurvey constructor.
     * @param $survey
     * @param $date
     * @param $hourMin
     * @param $hourMax
     * @param $everyday
     * @internal param string $surveyTitle
     */
    public function __construct($survey, $date, $hourMin, $hourMax, $everyday)
    {
        $this->survey = $survey;
        $this->date = $date;
        $this->hourMin = $hourMin;
        $this->hourMax = $hourMax;
        $this->everyday = $everyday;
    }

    /**
     * @param SurveyInterface $survey
     * @return boolean
     */
    public function isSurveyEligible(SurveyInterface $survey)
    {
        date_default_timezone_set('Europe/Paris');

        $hourMin = $this->getHourMin()->format('H:i:s');
        $hourMax = $this->getHourMax()->format('H:i:s');

        if (time() >= strtotime($hourMin) && time() <= strtotime($hourMax) && $this->survey === $survey) {

            if ($this->getDate() === null) {
                return true;
            }

            $dateCronSurvey = $this->getDate()->format('Y-m-d');

            $dateNow = new Datetime('now', new DateTimeZone('Europe/Paris'));
            $dateNow = $dateNow->format('Y-m-d');

            if ($dateCronSurvey === $dateNow) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return mixed
     */
    function jsonSerialize()
    {
        return [
            'id'        => $this->id,
            'survey'    => $this->survey,
            'date'      => $this->date,
            'hourMin'   => $this->hourMin,
            'hourMax'   => $this->hourMax,
            'everyday'  => $this->everyday
        ];
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
}