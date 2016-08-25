<?php

namespace AppBundle\Entity;

use AppBundle\Model\AccountInterface;
use AppBundle\Model\FileInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SurveyEntityRepository")
 * @ORM\Table(name="survey")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Survey implements SurveyInterface, \JsonSerializable
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"surveys_all", "surveys_summary"})
     */
    protected $id;

    /**
     * @var string The title of the survey.
     * @ORM\Column(type="string", name="title")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"surveys_all", "surveys_summary"})
     */
    protected $title;

    /**
     * @ORM\ManyToMany(targetEntity="Question", cascade={"persist", "remove"})
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("ArrayCollection<AppBundle\Entity\Question>")
     * @JMSSerializer\MaxDepth(4)
     * @JMSSerializer\Groups({"surveys_all"})
     */
    private $questions;

    /**
     * Survey constructor.
     * @param string $surveyTitle
     */
    public function __construct($surveyTitle)
    {
        $this->title = $surveyTitle;
        $this->questions = new ArrayCollection();
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
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param array $questions
     * @return SurveyInterface
     */
    public function addQuestions(array $questions)
    {
        foreach ($questions as $question) {
            $this->addQuestion($question);
        }
        return $this;
    }

    /**
     * @param QuestionInterface $question
     * @return SurveyInterface
     */
    public function addQuestion(QuestionInterface $question)
    {
        $this->questions->add($question);

        return $this;
    }

    /**
     * @param QuestionInterface $question
     * @return SurveyInterface
     */
    public function hasQuestion(QuestionInterface $question)
    {
        return $this->questions->contains($question);
    }

    /**
     * @param QuestionInterface $question
     * @return SurveyInterface
     */
    public function removeQuestion(QuestionInterface $question)
    {
        $this->questions->removeElement($question);

        return $this;
    }


    /**
     * @return SurveyInterface
     */
    public function removeAllQuestions()
    {
        foreach ($this->questions as $question) {
            $this->removeQuestion($question);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->title,
            'questions' => $this->questions,
        ];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function changeTitle($title)
    {
        $this->title = $title;
    }
}