<?php

namespace AppBundle\Repository\Restricted;

use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\AnswerRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RestrictedAnswerRepository extends RestrictedRepository implements AnswerRepositoryInterface, RepositoryInterface
{
    /**
     * @var AnswerRepositoryInterface
     */
    private $repository;

    public function __construct(
        AnswerRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function save(AnswerInterface $answer, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $answer);

        $this->repository->save($answer);
    }

    public function delete(AnswerInterface $answer, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $answer);

        $this->repository->delete($answer);
    }

    public function findOneById($id)
    {
        $answer = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $answer);

        return $answer;
    }

    /**
     * @param QuestionInterface $question
     * @return mixed
     */
    public function findAllForQuestion(QuestionInterface $question)
    {
        $answers = $this->repository->findAllForQuestion($question);

        foreach ($answers as $answer) {
            $this->denyAccessUnlessGranted('view', $answer);
        }

        return $answers;
    }

    /**
     * @param AnswerInterface $answer
     * @return mixed
     */
    public function refresh(AnswerInterface $answer)
    {
        $this->authorizationChecker->isGranted('view', $answer);

        $this->repository->refresh($answer);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        $answers = $this->repository->findBy($limit, $offset);

        foreach ($answers as $answer){
            $this->denyAccessUnlessGranted('view', $answer);
        }

        return $answers;
    }
}