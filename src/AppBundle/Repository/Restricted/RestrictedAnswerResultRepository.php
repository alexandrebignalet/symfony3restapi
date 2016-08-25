<?php

namespace AppBundle\Repository\Restricted;

use AppBundle\Model\AnswerResultInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\AnswerResultRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RestrictedAnswerResultRepository extends RestrictedRepository implements AnswerResultRepositoryInterface, RepositoryInterface
{
    /**
     * @var AnswerResultRepositoryInterface
     */
    private $repository;

    public function __construct(
        AnswerResultRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function save(AnswerResultInterface $answerResult, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $answerResult);

        $this->repository->save($answerResult);
    }

    public function delete(AnswerResultInterface $answerResult, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('admin', $answerResult);

        $this->repository->delete($answerResult);
    }

    public function findOneById($id)
    {
        $answerResult = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $answerResult);

        return $answerResult;
    }

    /**
     * @param AnswerResultInterface $answerResult
     * @return mixed
     */
    public function refresh(AnswerResultInterface $answerResult)
    {
        $this->authorizationChecker->isGranted('view', $answerResult);

        $this->repository->refresh($answerResult);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        $answerResults = $this->repository->findBy($limit, $offset);

        foreach ($answerResults as $answerResult){
            $this->denyAccessUnlessGranted('view', $answerResult);
        }

        return $answerResults;
    }
}