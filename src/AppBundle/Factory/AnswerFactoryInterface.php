<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 16:50
 */

namespace AppBundle\Factory;


use AppBundle\DTO\AnswerDTO;
use AppBundle\Model\QuestionInterface;

interface AnswerFactoryInterface
{
    /**
     * @param string $entitled
     * @param integer $value
     * @return mixed
     */
    public function create($entitled, $value);

    /**
     * @param AnswerDTO $answerDTO
     * @return mixed
     */
    public function createFromDTO(AnswerDTO $answerDTO);
}