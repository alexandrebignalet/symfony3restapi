<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 16:18
 */
namespace AppBundle\Entity;

use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\QuestionEntityRepository")
 * @ORM\Table(name="question")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Question implements QuestionInterface
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"questions_all", "questions_summary"})
     */
    protected $id;

    /**
     * @var string The entitled of the question.
     * @ORM\Column(type="string", name="entitled")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"questions_all", "questions_summary"})
     */
    protected $entitled;

    /**
     * @ORM\ManyToMany(targetEntity="Answer", cascade={"persist", "remove"})
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("ArrayCollection<AppBundle\Entity\Answer>")
     * @JMSSerializer\MaxDepth(4)
     * @JMSSerializer\Groups({"questions_all"})
     */
    private $answers;

    public function __construct($questionTitle)
    {
        $this->entitled = $questionTitle;
        $this->answers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEntitled()
    {
        return $this->entitled;
    }

    /**
     * @param AnswerInterface $answer
     * @return QuestionInterface
     */
    public function hasAnswer(AnswerInterface $answer)
    {
        return $this->answers->contains($answer);
    }

    /**
     * @param string $entitled
     */
    public function changeEntitled($entitled)
    {
        $this->entitled = $entitled;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param array $answers
     * @return QuestionInterface
     */
    public function addAnswers(array $answers)
    {
        foreach ($answers as $answer){
            $this->addAnswer($answer);
        }

        return $this;
    }

    /**
     * @param AnswerInterface $answer
     * @return QuestionInterface
     */
    public function addAnswer(AnswerInterface $answer)
    {
        $this->answers->add($answer);
    }

    /**
     * @param AnswerInterface $answer
     * @return QuestionInterface
     */
    public function removeAnswer(AnswerInterface $answer)
    {
        $this->answers->removeElement($answer);

        return $this;
    }

    /**
     * @return QuestionInterface
     */
    public function removeAllAnswers()
    {
        foreach ($this->answers as $answer){
            $this->removeAnswer($answer);
        }

        return $this;
    }
}