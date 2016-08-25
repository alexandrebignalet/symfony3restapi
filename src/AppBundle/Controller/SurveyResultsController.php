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
 * Class SurveyResultsController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("surveyresults")
 */
class SurveyResultsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get a single SurveyResult if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\SurveyResult",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerEnableMaxDepthChecks=true)
     *
     * @param int   $surveyResultId     the surveyResult id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($surveyResultId)
    {
        $surveyResult = $this->getSurveyResultHandler()->get($surveyResultId);

        $view = $this->view($surveyResult);

        return $view;
    }

    /**
     * Gets a collection of SurveyResults if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\SurveyResult",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @Annotations\View(serializerEnableMaxDepthChecks=true)
     *
     * @return View
     */
    public function cgetAction()
    {
        return $this->getSurveyResultHandler()->all(null, null);
    }

    /**
     * Creates a new SurveyResult by an User
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\SurveyResultType",
     *  output = "AppBundle\Entity\SurveyResult",
     *  statusCodes={
     *         201="Returned when a new SurveyResult has been successfully created",
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

            $surveyResult = $this->getSurveyResultHandler()->post($request->request->all());

            $routeOptions = [
                'surveyResultId'  => $surveyResult->getId(),
                '_format'    => $request->get('_format'),
            ];

            return $this->routeRedirectView('get_surveyresults', $routeOptions, Response::HTTP_CREATED);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing SurveyResult from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\SurveyResultType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the surveyResult id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id)
    {
        $requestedSurveyResult = $this->get('crv.repository.restricted_survey_result_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $surveyResult \AppBundle\Entity\SurveyResult */
            $surveyResult = $this->getSurveyResultHandler()->patch(
                $requestedSurveyResult,
                $request->request->all()
            );

            $routeOptions = array(
                'surveyResultId'    => $surveyResult->getId(),
                '_format'           => $request->get('_format')
            );

            return $this->routeRedirectView('get_surveyresults', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing SurveyResult from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\SurveyResultType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the surveyResult id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id)
    {
        $requestedSurveyResult = $this->get('crv.repository.restricted_survey_result_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $surveyResult \AppBundle\Entity\SurveyResult */
            $surveyResult = $this->getSurveyResultHandler()->put(
                $requestedSurveyResult,
                $request->request->all()
            );

            $routeOptions = array(
                'surveyResultId'    => $surveyResult->getId(),
                '_format'           => $request->get('_format')
            );

            return $this->routeRedirectView('get_survey_results', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Deletes a specific SurveyResult by ID if Admin
     *
     * @ApiDoc(
     *  description="Deletes an existing SurveyResult",
     *  statusCodes={
     *         204="Returned when an existing SurveyResult has been successfully deleted",
     *         403="Returned when trying to delete a non existent SurveyResult"
     *     }
     * )
     *
     * @param int $id the surveyResult id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $requestedAccount = $this->get('crv.repository.restricted_survey_result_repository')->findOneById($id);

        $this->getSurveyResultHandler()->delete($requestedAccount);

        return new View(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @return SurveyHandler
     */
    private function getSurveyResultHandler()
    {
        return $this->container->get('crv.handler.restricted_survey_result_handler');
    }
}