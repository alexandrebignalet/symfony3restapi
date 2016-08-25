<?php

namespace AppBundle\DTO;

use AppBundle\Model\QuestionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AnswerDTO implements DTOInterface, SymfonyFormDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $entitled;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    private $value;


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
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param integer $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}