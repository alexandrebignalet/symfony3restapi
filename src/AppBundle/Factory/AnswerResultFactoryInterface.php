<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:53
 */

namespace AppBundle\Factory;


use AppBundle\DTO\AnswerResultDTO;
use AppBundle\Entity\AnswerResult;
use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\UserInterface;

interface AnswerResultFactoryInterface
{
    /**
     * @param   QuestionInterface   $question
     * @param   AnswerInterface     $answer
     * @return  AnswerResult
     */
    public function create(QuestionInterface $question, AnswerInterface $answer);

    /**
     * @param  AnswerResultDTO    $surveyDTO
     * @return AnswerResult
     */
    public function createFromDTO(AnswerResultDTO $surveyDTO);
}