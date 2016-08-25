<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\AnswerEntityRepository;
use AppBundle\Model\AnswerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\AnswerRepositoryInterface;

class DoctrineAnswerRepository implements AnswerRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var AnswerEntityRepository
     */
    private $answerEntityRepository;


    /**
     * DoctrineAnswerRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param AnswerEntityRepository $answerEntityRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository, AnswerEntityRepository $answerEntityRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->answerEntityRepository = $answerEntityRepository;
    }

    /**
     * @param  AnswerInterface $answer
     * @return mixed|void
     */
    public function refresh(AnswerInterface $answer)
    {
        $this->commonRepository->refresh($answer);
    }

    /**
     * @param   AnswerInterface       $answer
     * @param   array               $arguments
     */
    public function save(AnswerInterface $answer, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($answer, $arguments);
    }

    /**
     * @param   AnswerInterface       $answer
     * @param   array               $arguments
     */
    public function delete(AnswerInterface $answer, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($answer, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:Answer')->find($id);
    }

    /**
     * @param QuestionInterface $question
     * @return mixed
     */
    public function findAllForQuestion(QuestionInterface $question)
    {
        // TODO: Implement findAllForQuestion() method.
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:Answer')->findBy([], null, $limit, $offset);
    }
}