<?php

namespace AppBundle\Repository\Restricted;

use AppBundle\Model\CronSurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\CronSurveyRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class RestrictedCronSurveyRepository extends RestrictedRepository implements CronSurveyRepositoryInterface, RepositoryInterface
{
    /**
     * @var CronSurveyRepositoryInterface
     */
    private $repository;

    public function __construct(
        CronSurveyRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function save(CronSurveyInterface $cronSurvey, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $cronSurvey);

        $this->repository->save($cronSurvey);
    }

    public function delete(CronSurveyInterface $cronSurvey, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $cronSurvey);

        $this->repository->delete($cronSurvey);
    }

    public function findOneById($id)
    {
        $cronSurvey = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $cronSurvey);

        return $cronSurvey;
    }

    /**
     * @param CronSurveyInterface $cronSurvey
     * @return mixed
     */
    public function refresh(CronSurveyInterface $cronSurvey)
    {
        $this->authorizationChecker->isGranted('view', $cronSurvey);

        $this->repository->refresh($cronSurvey);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        $cronSurveys = $this->repository->findBy($limit, $offset);

        foreach ($cronSurveys as $cronSurvey){
            $this->denyAccessUnlessGranted('view', $cronSurvey);
        }

        return $cronSurveys;
    }

}