<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\CronSurveyEntityRepository;
use AppBundle\Model\CronSurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\CronSurveyRepositoryInterface;

class DoctrineCronSurveyRepository implements CronSurveyRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var CronSurveyEntityRepository
     */
    private $cronSurveyEntityRepository;


    /**
     * DoctrineCronSurveyRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param CronSurveyEntityRepository $cronSurveyEntityRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository, CronSurveyEntityRepository $cronSurveyEntityRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->cronSurveyEntityRepository = $cronSurveyEntityRepository;
    }

    /**
     * @param  CronSurveyInterface $cronSurvey
     * @return mixed|void
     */
    public function refresh(CronSurveyInterface $cronSurvey)
    {
        $this->commonRepository->refresh($cronSurvey);
    }

    /**
     * @param   CronSurveyInterface       $cronSurvey
     * @param   array               $arguments
     */
    public function save(CronSurveyInterface $cronSurvey, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($cronSurvey, $arguments);
    }

    /**
     * @param   CronSurveyInterface       $cronSurvey
     * @param   array               $arguments
     */
    public function delete(CronSurveyInterface $cronSurvey, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($cronSurvey, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:CronSurvey')->find($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:CronSurvey')->findBy([], null, $limit, $offset);
    }
}