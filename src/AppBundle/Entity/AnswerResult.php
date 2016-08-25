<?php

namespace AppBundle\Entity;


use AppBundle\Model\AnswerInterface;
use AppBundle\Model\AnswerResultInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AnswerResultEntityRepository")
 * @ORM\Table(name="answer_result")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class AnswerResult implements AnswerResultInterface, \JsonSerializable
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMSSerializer\Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     * @JMSSerializer\Type("AppBundle\Entity\Question")
     * @JMSSerializer\MaxDepth(2)
     * @JMSSerializer\Expose
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="Answer")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     * })
     * @JMSSerializer\Type("AppBundle\Entity\Answer")
     * @JMSSerializer\MaxDepth(2)
     * @JMSSerializer\Expose
     */
    private $answer;

    public function __construct(QuestionInterface $question, AnswerInterface $answer)
    {
        $this->answer = $answer;
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AnswerInterface
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param QuestionInterface $question
     */
    public function setQuestion(QuestionInterface $question)
    {
        $this->question = $question;
    }

    /**
     * @return QuestionInterface
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param AnswerInterface $answer
     */
    public function setAnswer(AnswerInterface $answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'answer'        => $this->answer,
            'question'      => $this->question
        ];
    }
}