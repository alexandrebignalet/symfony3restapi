<?php

namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\SurveyResultEntityRepository;
use AppBundle\Model\SurveyInterface;
use AppBundle\Model\SurveyResultInterface;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\SurveyResultRepositoryInterface;
use DateTime;

class DoctrineSurveyResultRepository implements SurveyResultRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var SurveyResultEntityRepository
     */
    private $surveyResultEntityRepository;


    /**
     * DoctrineSurveyResultRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param SurveyResultEntityRepository $surveyResultEntityRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository, SurveyResultEntityRepository $surveyResultEntityRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->surveyResultEntityRepository = $surveyResultEntityRepository;
    }

    /**
     * @param  SurveyResultInterface $surveyResult
     * @return mixed|void
     */
    public function refresh(SurveyResultInterface $surveyResult)
    {
        $this->commonRepository->refresh($surveyResult);
    }

    /**
     * @param   SurveyResultInterface       $surveyResult
     * @param   array               $arguments
     */
    public function save(SurveyResultInterface $surveyResult, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($surveyResult, $arguments);
    }

    /**
     * @param   SurveyResultInterface       $surveyResult
     * @param   array               $arguments
     */
    public function delete(SurveyResultInterface $surveyResult, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($surveyResult, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:SurveyResult')->find($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function findBy($limit, $offset)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:SurveyResult')->findBy([], null, $limit, $offset);
    }

    /**
     * @param UserInterface $user
     * @param SurveyInterface $survey
     * @return bool
     */
    public function userHasAlreadyCompleteThisSurvey(UserInterface $user, SurveyInterface $survey)
    {
        $date = new DateTime();

        $qb = $this->commonRepository->getEntityManager()->createQueryBuilder();

        $qb->select('sr')
            ->from('AppBundle:SurveyResult', 'sr')
            ->where('sr.user = :user')
            ->andWhere('sr.survey = :survey')
            ->andWhere('sr.completed = 1')
            ->andWhere('sr.date > :dateMin')
            ->andWhere('sr.date < :dateMax')
            ->setParameters([
                'user'      => $user,
                'survey'    => $survey,
                'dateMin'      => $date->format('Y-m-d 00:00:00'),
                'dateMax'      => $date->format('Y-m-d 23:59:59')
            ]);

        $query = $qb->getQuery();

        $result = $query->execute();

        if( sizeof($result) === 0 )
        {
            return false;
        }

        return true;
    }
}