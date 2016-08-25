<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\SurveyEntityRepository;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\SurveyRepositoryInterface;

class DoctrineSurveyRepository implements SurveyRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var SurveyEntityRepository
     */
    private $surveyEntityRepository;


    /**
     * DoctrineSurveyRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param SurveyEntityRepository $surveyEntityRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository, SurveyEntityRepository $surveyEntityRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->surveyEntityRepository = $surveyEntityRepository;
    }

    /**
     * @param  SurveyInterface $survey
     * @return mixed|void
     */
    public function refresh(SurveyInterface $survey)
    {
        $this->commonRepository->refresh($survey);
    }

    /**
     * @param   SurveyInterface       $survey
     * @param   array               $arguments
     */
    public function save(SurveyInterface $survey, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($survey, $arguments);
    }

    /**
     * @param   SurveyInterface       $survey
     * @param   array               $arguments
     */
    public function delete(SurveyInterface $survey, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($survey, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:Survey')->find($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:Survey')->findBy([], null, $limit, $offset);
    }
}