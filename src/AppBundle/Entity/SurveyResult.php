<?php

namespace AppBundle\Entity;


use AppBundle\Model\AnswerResultInterface;
use AppBundle\Model\SurveyResultInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\UserInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SurveyResultEntityRepository")
 * @ORM\Table(name="survey_result")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class SurveyResult implements SurveyResultInterface, \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     * @JMSSerializer\Type("AppBundle\Entity\User")
     * @JMSSerializer\MaxDepth(2)
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="AnswerResult", cascade={"persist"})
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("ArrayCollection<AppBundle\Entity\AnswerResult>")
     * @JMSSerializer\MaxDepth(4)
     */
    private $answerResults;

    /**
     * @var datetime The date when the survey has been submitted.
     * @ORM\Column(type="datetime", name="date")
     * @JMSSerializer\Expose
     */
    protected $date;

    /**
     * @var boolean If the survey has been submitted by this user.
     * @ORM\Column(type="boolean", name="completed")
     * @JMSSerializer\Expose
     */
    protected $completed;

    public function __construct(UserInterface $user, SurveyInterface $survey)
    {
        $this->user = $user;
        $this->survey = $survey;
        $this->date = new DateTime();
        $this->answerResults = new ArrayCollection();
        $this->completed = true;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
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
     */
    public function setSurvey(SurveyInterface $survey)
    {
        $this->survey = $survey;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswerResults()
    {
       return $this->answerResults;
    }

    /**
     * @param array $answerResults
     * @return SurveyResultInterface
     */
    public function addAnswerResults(array $answerResults)
    {
        foreach ($answerResults as $answerResult){
            $this->addAnswerResult($answerResult);
        }
        return $this;
    }

    /**
     * @param AnswerResultInterface $answerResult
     * @return SurveyResultInterface
     */
    public function addAnswerResult(AnswerResultInterface $answerResult)
    {
        $this->answerResults->add($answerResult);

        return $this;
    }

    /**
     * @param AnswerResultInterface $answerResult
     * @return SurveyResultInterface
     */
    public function removeAnswerResult(AnswerResultInterface $answerResult)
    {
        $this->answerResults->removeElement($answerResult);

        return $this;
    }

    /**
     * @return SurveyResultInterface
     */
    public function removeAllAnswerResults()
    {
        foreach ($this->answerResults as $answerResult){
            $this->removeAnswerResult($answerResult);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'user'          => $this->user,
            'survey'        => $this->survey,
            'answerResults' => $this->answerResults,
            'date'          => $this->date,
            'completed'     => $this->completed
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
     * @return boolean
     */
    public function isCompleted()
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