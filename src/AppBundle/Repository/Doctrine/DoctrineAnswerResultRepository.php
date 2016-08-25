<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\AnswerResultEntityRepository;
use AppBundle\Model\AnswerResultInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\AnswerResultRepositoryInterface;

class DoctrineAnswerResultRepository implements AnswerResultRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var AnswerResultEntityRepository
     */
    private $answerResultEntityRepository;


    /**
     * DoctrineAnswerResultRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param AnswerResultEntityRepository $answerResultEntityRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository, AnswerResultEntityRepository $answerResultEntityRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->answerResultEntityRepository = $answerResultEntityRepository;
    }

    /**
     * @param  AnswerResultInterface $answerResult
     * @return mixed|void
     */
    public function refresh(AnswerResultInterface $answerResult)
    {
        $this->commonRepository->refresh($answerResult);
    }

    /**
     * @param   AnswerResultInterface       $answerResult
     * @param   array               $arguments
     */
    public function save(AnswerResultInterface $answerResult, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($answerResult, $arguments);
    }

    /**
     * @param   AnswerResultInterface       $answerResult
     * @param   array               $arguments
     */
    public function delete(AnswerResultInterface $answerResult, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($answerResult, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:AnswerResult')->find($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:AnswerResult')->findBy([], null, $limit, $offset);
    }
}