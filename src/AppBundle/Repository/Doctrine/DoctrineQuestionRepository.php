<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\QuestionEntityRepository;
use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\QuestionRepositoryInterface;

class DoctrineQuestionRepository implements QuestionRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var QuestionEntityRepository
     */
    private $questionEntityRepository;


    /**
     * DoctrineQuestionRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param QuestionEntityRepository $questionEntityRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository, QuestionEntityRepository $questionEntityRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->questionEntityRepository = $questionEntityRepository;
    }

    /**
     * @param  QuestionInterface $question
     * @return mixed|void
     */
    public function refresh(QuestionInterface $question)
    {
        $this->commonRepository->refresh($question);
    }

    /**
     * @param   QuestionInterface       $question
     * @param   array               $arguments
     */
    public function save(QuestionInterface $question, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($question, $arguments);
    }

    /**
     * @param   QuestionInterface       $question
     * @param   array               $arguments
     */
    public function delete(QuestionInterface $question, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($question, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:Question')->find($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:Question')->findBy([], null, $limit, $offset);
    }

    /**
     * @param SurveyInterface $survey
     * @return mixed
     */
    public function findAllForSurvey(SurveyInterface $survey)
    {
        // TODO: Implement findAllForSurvey() method.
    }
}