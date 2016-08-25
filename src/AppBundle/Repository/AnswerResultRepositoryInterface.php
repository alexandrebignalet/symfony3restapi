<?php

namespace AppBundle\Repository;

use AppBundle\Model\AnswerResultInterface;

/**
 * Interface AnswerResultRepositoryInterface
 * @package AppBundle\Repository
 */
interface AnswerResultRepositoryInterface
{
    /**
     * @param AnswerResultInterface $answerResult
     * @return mixed
     */
    public function refresh(AnswerResultInterface $answerResult);

    /**
     * @param AnswerResultInterface      $answerResult
     * @param array                 $arguments
     */
    public function save(AnswerResultInterface $answerResult, array $arguments = []);

    /**
     * @param AnswerResultInterface      $answerResult
     * @param array                 $arguments
     */
    public function delete(AnswerResultInterface $answerResult, array $arguments = []);

    /**
     * @param                       $id
     * @return                      mixed|null
     */
    public function findOneById($id);

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset);
}