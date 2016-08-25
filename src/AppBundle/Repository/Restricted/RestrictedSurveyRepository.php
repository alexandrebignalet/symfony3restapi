<?php

namespace AppBundle\Repository\Restricted;

use AppBundle\Business\SurveyAvailabilityCheckerInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\SurveyRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RestrictedSurveyRepository extends RestrictedRepository implements SurveyRepositoryInterface, RepositoryInterface
{
    /**
     * @var SurveyRepositoryInterface
     */
    private $repository;


    public function __construct(
        SurveyRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker,
        SurveyAvailabilityCheckerInterface $surveyAvailabilityChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
        $this->surveyAvailabilityChecker = $surveyAvailabilityChecker;
    }

    public function save(SurveyInterface $survey, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $survey);

        $survey = $this->denyOneSurveyAccessOutOfTheHour($survey);

        $this->repository->save($survey);
    }

    public function delete(SurveyInterface $survey, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $survey);

        $survey = $this->denyOneSurveyAccessOutOfTheHour($survey);

        $this->repository->delete($survey);
    }

    public function findOneById($id)
    {
        /** @var SurveyInterface $survey */
        $survey = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $survey);

        $survey = $this->denyOneSurveyAccessOutOfTheHour($survey);

        return $survey;
    }

    /**
     * @param SurveyInterface $survey
     * @return mixed
     */
    public function refresh(SurveyInterface $survey)
    {
        $this->authorizationChecker->isGranted('view', $survey);

        $survey = $this->denyOneSurveyAccessOutOfTheHour($survey);

        $this->repository->refresh($survey);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        $surveys = $this->repository->findBy($limit, $offset);

        foreach ($surveys as $survey)
        {
            $this->denyAccessUnlessGranted('view', $survey);
        }

        $surveys = $this->denySurveysAccessOutOfTheHour($surveys);

        return $surveys;
    }
}