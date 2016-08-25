<?php

namespace AppBundle\Repository\Restricted;


use AppBundle\Business\SurveyAvailabilityCheckerInterface;
use AppBundle\Exception\AnySurveyAvailableException;
use AppBundle\Model\SurveyInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class RestrictedRepository
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var SurveyAvailabilityCheckerInterface
     */
    protected $surveyAvailabilityChecker;

    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied object.
     *
     * @param mixed  $attributes The attributes
     * @param mixed  $object     The object
     * @param string $message    The message passed to the exception
     *
     * @throws AccessDeniedHttpException
     */
    protected function denyAccessUnlessGranted($attributes, $object = null, $message = 'Access Denied.')
    {
        if (!$this->authorizationChecker->isGranted($attributes, $object)) {
            throw new AccessDeniedHttpException($message);
        }
    }

    /**
     * Return a message if the User try to access the survey before the hour.
     *
     * @param SurveyInterface $survey
     * @return string
     */
    protected function denyOneSurveyAccessOutOfTheHour(SurveyInterface $survey)
    {
        if ( !$this->surveyAvailabilityChecker->isAvailable($survey) )
        {
            throw new AnySurveyAvailableException();
        }

        return $survey;
    }

    /**
     * Return a message if the User try to access the survey before the hour.
     *
     * @param array $surveys
     * @return string
     */
    protected function denySurveysAccessOutOfTheHour(array $surveys)
    {
        $surveysAvailable = $this->surveyAvailabilityChecker->getSurveysAvailable($surveys);

        if ( sizeof($surveysAvailable) === 0 )
        {
            throw new AnySurveyAvailableException();
        }

        return $surveysAvailable;
    }
}