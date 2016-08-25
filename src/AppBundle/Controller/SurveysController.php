<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use AppBundle\Handler\SurveyHandler;
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
 * Class SurveysController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("surveys")
 */
class SurveysController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get a single Survey.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Survey",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerGroups={
     *   "surveys_all",
     *   "questions_summary"
     * }, serializerEnableMaxDepthChecks=true)
     *
     * @param int   $surveyId     the survey id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($surveyId)
    {
        $survey = $this->getSurveyHandler()->get($surveyId);

        $view = $this->view($survey);

        return $view;
    }

    /**
     * Gets a collection of Surveys if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Survey",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerGroups={
     *   "surveys_all",
     *   "questions_all",
     *   "answers_summary"
     * }, serializerEnableMaxDepthChecks=true)
     *
     * @throws NotFoundHttpException when does not exist
     *
     *
     * @return View
     */
    public function cgetAction()
    {
        return $this->getSurveyHandler()->all(null, null);
    }

    /**
     * Creates a new Survey by an Admin
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\SurveyType",
     *  output = "AppBundle\Entity\Survey",
     *  statusCodes={
     *         201="Returned when a new Survey has been successfully created",
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

            $survey = $this->getSurveyHandler()->post($request->request->all());

            $routeOptions = [
                'surveyId'  => $survey->getId(),
                '_format'    => $request->get('_format'),
            ];

            return $this->routeRedirectView('get_surveys', $routeOptions, Response::HTTP_CREATED);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing Survey from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\SurveyType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the survey id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id)
    {
        $requestedSurvey = $this->get('crv.repository.restricted_survey_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $survey \AppBundle\Entity\Survey */
            $survey = $this->getSurveyHandler()->patch(
                $requestedSurvey,
                $request->request->all()
            );

            $routeOptions = array(
                'surveyId'        => $survey->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_surveys', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing Survey from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\SurveyType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the survey id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id)
    {
        $requestedSurvey = $this->get('crv.repository.restricted_survey_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $survey \AppBundle\Entity\Survey */
            $survey = $this->getSurveyHandler()->put(
                $requestedSurvey,
                $request->request->all()
            );

            $routeOptions = array(
                'surveyId'        => $survey->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_surveys', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Deletes a specific Survey by ID
     *
     * @ApiDoc(
     *  description="Deletes an existing Survey",
     *  statusCodes={
     *         204="Returned when an existing Survey has been successfully deleted",
     *         403="Returned when trying to delete a non existent Survey"
     *     }
     * )
     *
     * @param int $id the survey id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $requestedAccount = $this->get('crv.repository.restricted_survey_repository')->findOneById($id);

        $this->getSurveyHandler()->delete($requestedAccount);

        return new View(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @return SurveyHandler
     */
    private function getSurveyHandler()
    {
        return $this->container->get('crv.handler.restricted_survey_handler');
    }
}