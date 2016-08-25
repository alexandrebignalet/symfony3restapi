<?php

namespace AppBundle\Repository;

use AppBundle\Model\QuestionInterface;
use AppBundle\Model\SurveyInterface;

/**
 * Interface QuestionRepositoryInterface
 * @package AppBundle\Repository
 */
interface QuestionRepositoryInterface
{
    /**
     * @param QuestionInterface $question
     * @return mixed
     */
    public function refresh(QuestionInterface $question);

    /**
     * @param QuestionInterface      $question
     * @param array                 $arguments
     */
    public function save(QuestionInterface $question, array $arguments = []);

    /**
     * @param QuestionInterface      $question
     * @param array                 $arguments
     */
    public function delete(QuestionInterface $question, array $arguments = []);

    /**
     * @param                       $id
     * @return                      mixed|null
     */
    public function findOneById($id);


    /**
     * @param SurveyInterface $survey
     * @return mixed
     */
    public function findAllForSurvey(SurveyInterface $survey);

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset);
}