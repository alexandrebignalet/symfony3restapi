<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 11/08/2016
 * Time: 09:29
 */

namespace AppBundle\Repository;


use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;

interface AnswerRepositoryInterface
{
    /**
     * @param AnswerInterface $answer
     * @return mixed
     */
    public function refresh(AnswerInterface $answer);

    /**
     * @param AnswerInterface      $answer
     * @param array                 $arguments
     */
    public function save(AnswerInterface $answer, array $arguments = []);

    /**
     * @param AnswerInterface      $answer
     * @param array                 $arguments
     */
    public function delete(AnswerInterface $answer, array $arguments = []);

    /**
     * @param                       $id
     * @return                      mixed|null
     */
    public function findOneById($id);


    /**
     * @param QuestionInterface $question
     * @return mixed
     */
    public function findAllForQuestion(QuestionInterface $question);

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset);
}