<?php

namespace AppBundle\Repository;

use AppBundle\Model\SurveyInterface;

/**
 * Interface SurveyRepositoryInterface
 * @package AppBundle\Repository
 */
interface SurveyRepositoryInterface
{
    /**
     * @param SurveyInterface $survey
     * @return mixed
     */
    public function refresh(SurveyInterface $survey);

    /**
     * @param SurveyInterface      $survey
     * @param array                 $arguments
     */
    public function save(SurveyInterface $survey, array $arguments = []);

    /**
     * @param SurveyInterface      $survey
     * @param array                 $arguments
     */
    public function delete(SurveyInterface $survey, array $arguments = []);

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