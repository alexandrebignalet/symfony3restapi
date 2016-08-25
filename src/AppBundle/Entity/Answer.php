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
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AnswerEntityRepository")
 * @ORM\Table(name="answer")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Answer implements AnswerInterface
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"answers_all", "answers_summary"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="entitled")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"answers_all", "answers_summary"})
     */
    protected $entitled;

    /**
     * @ORM\Column(type="string", name="value")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"answers_all", "answers_summary"})
     */
    protected $value;


    /**
     * Answer constructor.
     * @param string $answerEntitled
     * @param int $answerValue
     */
    public function __construct($answerEntitled, $answerValue)
    {
        $this->entitled = $answerEntitled;
        $this->value = $answerValue;
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
     * @param string $entitled
     */
    public function changeEntitled($entitled)
    {
        $this->entitled = $entitled;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}