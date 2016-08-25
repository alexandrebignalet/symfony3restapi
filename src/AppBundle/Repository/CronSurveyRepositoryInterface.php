<?php

namespace AppBundle\Repository;

use AppBundle\Model\CronSurveyInterface;

/**
 * Interface CronSurveyRepositoryInterface
 * @package AppBundle\Repository
 */
interface CronSurveyRepositoryInterface
{
    /**
     * @param CronSurveyInterface $cronSurvey
     * @return mixed
     */
    public function refresh(CronSurveyInterface $cronSurvey);

    /**
     * @param CronSurveyInterface      $cronSurvey
     * @param array                 $arguments
     */
    public function save(CronSurveyInterface $cronSurvey, array $arguments = []);

    /**
     * @param CronSurveyInterface      $cronSurvey
     * @param array                 $arguments
     */
    public function delete(CronSurveyInterface $cronSurvey, array $arguments = []);

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