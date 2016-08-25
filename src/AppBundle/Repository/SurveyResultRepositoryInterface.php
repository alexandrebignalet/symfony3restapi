<?php

namespace AppBundle\Repository;

use AppBundle\Model\SurveyInterface;
use AppBundle\Model\SurveyResultInterface;
use AppBundle\Model\UserInterface;
use Datetime;

/**
 * Interface SurveyResultRepositoryInterface
 * @package AppBundle\Repository
 */
interface SurveyResultRepositoryInterface
{
    /**
     * @param SurveyResultInterface $surveyResult
     * @return mixed
     */
    public function refresh(SurveyResultInterface $surveyResult);

    /**
     * @param SurveyResultInterface      $surveyResult
     * @param array                 $arguments
     */
    public function save(SurveyResultInterface $surveyResult, array $arguments = []);

    /**
     * @param SurveyResultInterface      $surveyResult
     * @param array                 $arguments
     */
    public function delete(SurveyResultInterface $surveyResult, array $arguments = []);

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

    /**
     * @param UserInterface $user
     * @param SurveyInterface $survey
     * @return bool
     */
    public function userHasAlreadyCompleteThisSurvey(UserInterface $user, SurveyInterface $survey);

}