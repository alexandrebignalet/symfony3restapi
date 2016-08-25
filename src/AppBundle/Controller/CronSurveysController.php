<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use AppBundle\Handler\CronSurveyHandler;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CronSurveysController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("cronsurveys")
 */
class CronSurveysController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get a single CronSurvey.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\CronSurvey",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerEnableMaxDepthChecks=true)
     *
     * @param int   $cronSurveyId     the cron survey id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($cronSurveyId)
    {
        $cronSurveyId = $this->getCronSurveyHandler()->get($cronSurveyId);

        $view = $this->view($cronSurveyId);

        return $view;
    }

    /**
     * Gets a collection of CronSurveys if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\CronSurvey",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerEnableMaxDepthChecks=true)
     *
     * @throws NotFoundHttpException when does not exist
     *
     *
     * @return View
     */
    public function cgetAction()
    {
        return $this->getCronSurveyHandler()->all(null, null);
    }

    /**
     * Creates a new CronSurvey by an Admin
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\CronSurveyType",
     *  output = "AppBundle\Entity\CronSurvey",
     *  statusCodes={
     *         201="Returned when a new CronSurvey has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
     * @param Request $request
     * @return View
     */
    public function postAction(Request $request)
    {
        try {

            $cronSurvey = $this->getCronSurveyHandler()->post($request->request->all());

            $routeOptions = [
                'cronSurveyId'  => $cronSurvey->getId(),
                '_format'    => $request->get('_format'),
            ];

            return $this->routeRedirectView('get_cronsurveys', $routeOptions, Response::HTTP_CREATED);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing CronSurvey from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\CronSurveyType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the cron survey id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id)
    {
        $requestedCronSurvey = $this->get('crv.repository.restricted_cron_survey_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $cronSurvey \AppBundle\Entity\CronSurvey */
            $cronSurvey = $this->getCronSurveyHandler()->patch(
                $requestedCronSurvey,
                $request->request->all()
            );

            $routeOptions = array(
                'cronSurveyId'        => $cronSurvey->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_cronsurveys', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing CronSurvey from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\CronSurveyType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the cron survey id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id)
    {
        $requestedCronSurvey = $this->get('crv.repository.restricted_cron_survey_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $cronsurvey \AppBundle\Entity\CronSurvey */
            $cronSurvey = $this->getCronSurveyHandler()->put(
                $requestedCronSurvey,
                $request->request->all()
            );

            $routeOptions = array(
                'cronSurveyId'        => $cronSurvey->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_cronsurveys', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Deletes a specific CronSurvey by ID
     *
     * @ApiDoc(
     *  description="Deletes an existing CronSurvey",
     *  statusCodes={
     *         204="Returned when an existing CronSurvey has been successfully deleted",
     *         403="Returned when trying to delete a non existent CronSurvey"
     *     }
     * )
     *
     * @param int $id the cron survey id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $requestedAccount = $this->get('crv.repository.restricted_cron_survey_repository')->findOneById($id);

        $this->getCronSurveyHandler()->delete($requestedAccount);

        return new View(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @return CronSurveyHandler
     */
    private function getCronSurveyHandler()
    {
        return $this->container->get('crv.handler.restricted_cron_survey_handler');
    }
}