<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/08/2016
 * Time: 18:08
 */

namespace AppBundle\Business;


use AppBundle\Model\CronSurveyInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\CronSurveyRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SurveyAvailabilityChecker implements SurveyAvailabilityCheckerInterface
{

    /**
     * @var CronSurveyRepositoryInterface $cronSurveyRepository
     */
    private $cronSurveyRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * SurveyAvailabilityChecker constructor.
     * @param CronSurveyRepositoryInterface $cronSurveyRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(CronSurveyRepositoryInterface $cronSurveyRepository, TokenStorageInterface $tokenStorage)
    {
        $this->cronSurveyRepository = $cronSurveyRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param SurveyInterface $survey
     * @return boolean
     * is the survey is available?
     */
    public function isAvailable(SurveyInterface $survey)
    {
        /***
         * User can submit only one survey in a day
         */
        $user = $this->tokenStorage->getToken()->getUser();

        if( $user->hasRole('ROLE_SUPER_ADMIN') || $user->hasRole('ROLE_ADMIN') )
        {
            return true;
        }

        $cronSurveys = $this->cronSurveyRepository->findBy(null, null);



        $isEligible = false;

        /** @var CronSurveyInterface $cronSurvey */
        foreach ( $cronSurveys as $cronSurvey )
        {
            if( $cronSurvey->isSurveyEligible($survey) )
            {
                $isEligible = true;
            }
        }

        return $isEligible;
    }

    /**
     * @param array $surveys
     * @return mixed
     * Return all the surveys available
     */
    public function getSurveysAvailable(array $surveys)
    {
        $surveysReturned = [];

        /** @var SurveyInterface $survey */
        foreach ($surveys as $survey)
        {
            if( $this->isAvailable($survey) )
            {
                $surveysReturned[] = $survey;
            }
        }

        return $surveysReturned;
    }
}