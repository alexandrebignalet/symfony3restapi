<?php

namespace AppBundle\Repository\Restricted;

use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\QuestionRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RestrictedQuestionRepository extends RestrictedRepository implements QuestionRepositoryInterface, RepositoryInterface
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $repository;

    public function __construct(
        QuestionRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function save(QuestionInterface $question, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $question);

        $this->repository->save($question);
    }

    public function delete(QuestionInterface $question, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $question);

        $this->repository->delete($question);
    }

    public function findOneById($id)
    {
        $question = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $question);

        return $question;
    }

    /**
     * @param SurveyInterface $survey
     * @return mixed
     */
    public function findAllForSurvey(SurveyInterface $survey)
    {
        $questions = $this->repository->findAllForSurvey($survey);

        foreach ($questions as $question) {
            $this->denyAccessUnlessGranted('view', $question);
        }

        return $questions;
    }

    /**
     * @param QuestionInterface $question
     * @return mixed
     */
    public function refresh(QuestionInterface $question)
    {
        $this->authorizationChecker->isGranted('view', $question);

        $this->repository->refresh($question);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        $questions = $this->repository->findBy($limit, $offset);

        foreach ($questions as $question){
            $this->denyAccessUnlessGranted('view', $question);
        }

        return $questions;
    }
}