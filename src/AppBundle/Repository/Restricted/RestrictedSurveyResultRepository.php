<?php

namespace AppBundle\Repository\Restricted;


use AppBundle\Business\SurveyAvailabilityCheckerInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\SurveyResultInterface;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\SurveyResultRepositoryInterface;
use DateTime;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RestrictedSurveyResultRepository extends RestrictedRepository implements SurveyResultRepositoryInterface, RepositoryInterface
{
    /**
     * @var SurveyResultRepositoryInterface $repository
     */
    private $repository;

    /**
     * RestrictedSurveyResultRepository constructor.
     * @param SurveyResultRepositoryInterface $repository
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param SurveyAvailabilityCheckerInterface $surveyAvailabilityChecker
     */
    public function __construct(
        SurveyResultRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker,
        SurveyAvailabilityCheckerInterface $surveyAvailabilityChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
        $this->surveyAvailabilityChecker = $surveyAvailabilityChecker;
    }

    public function save(SurveyResultInterface $surveyResult, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $surveyResult);

        $this->denyOneSurveyAccessOutOfTheHour($surveyResult->getSurvey());

        if ( $this->userHasAlreadyCompleteThisSurvey($surveyResult->getUser(), $surveyResult->getSurvey())){
            throw new AccessDeniedHttpException('This survey has already been submit today.');
        }

        $this->repository->save($surveyResult);
    }

    public function delete(SurveyResultInterface $surveyResult, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('view', $surveyResult);

        $this->repository->delete($surveyResult);
    }

    public function findOneById($id)
    {
        $surveyResult = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $surveyResult);

        return $surveyResult;
    }

    /**
     * @param SurveyResultInterface $surveyResult
     * @return mixed
     */
    public function refresh(SurveyResultInterface $surveyResult)
    {
        $this->authorizationChecker->isGranted('view', $surveyResult);

        $this->repository->refresh($surveyResult);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        $surveyResults = $this->repository->findBy($limit, $offset);

        foreach ($surveyResults as $surveyResult){
            $this->denyAccessUnlessGranted('view', $surveyResult);
        }

        return $surveyResults;
    }

    /**
     * @param UserInterface $user
     * @param SurveyInterface $survey
     * @return bool
     */
    public function userHasAlreadyCompleteThisSurvey(UserInterface $user, SurveyInterface $survey)
    {
        /** @var boolean $userHasAlreadyCompleteThisSurvey */
        $userHasAlreadyCompleteThisSurvey = $this->repository->userHasAlreadyCompleteThisSurvey($user, $survey);

        return $userHasAlreadyCompleteThisSurvey;
    }
}