<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use AppBundle\Handler\AnswerHandler;
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
 * Class AnswersController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("answers")
 */
class AnswersController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get a single Answer.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Answer",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *  @Annotations\View(serializerGroups={
     *   "answers_all",
     *   "questions_summary"
     * }, serializerEnableMaxDepthChecks=true)
     *
     * @param int   $answerId     the answer id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($answerId)
    {
        $answer = $this->getAnswerHandler()->get($answerId);

        $view = $this->view($answer);

        return $view;
    }

    /**
     * Gets a collection of Answers if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Answer",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerGroups={
     *   "answers_all",
     *   "questions_summary"
     * })
     *
     * @throws NotFoundHttpException when does not exist
     *
     *
     * @return View
     */
    public function cgetAction()
    {
        return $this->getAnswerHandler()->all(null, null);
    }

    /**
     * Creates a new Answer by an Admin
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\AnswerType",
     *  output = "AppBundle\Entity\Answer",
     *  statusCodes={
     *         201="Returned when a new Answer has been successfully created",
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

            $answer = $this->getAnswerHandler()->post($request->request->all());

            $routeOptions = [
                'answerId'  => $answer->getId(),
                '_format'    => $request->get('_format'),
            ];

            return $this->routeRedirectView('get_answers', $routeOptions, Response::HTTP_CREATED);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing Answer from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\AnswerType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the answer id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id)
    {
        $requestedAnswer = $this->get('crv.repository.restricted_answer_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $answer \AppBundle\Entity\Answer */
            $answer = $this->getAnswerHandler()->patch(
                $requestedAnswer,
                $request->request->all()
            );

            $routeOptions = array(
                'answerId'        => $answer->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_answers', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing Answer from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\AnswerType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the answer id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id)
    {
        $requestedAnswer = $this->get('crv.repository.restricted_answer_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $answer \AppBundle\Entity\Answer */
            $answer = $this->getAnswerHandler()->put(
                $requestedAnswer,
                $request->request->all()
            );

            $routeOptions = array(
                'answerId'        => $answer->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_answers', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Deletes a specific Answer by ID
     *
     * @ApiDoc(
     *  description="Deletes an existing Answer",
     *  statusCodes={
     *         204="Returned when an existing Answer has been successfully deleted",
     *         403="Returned when trying to delete a non existent Answer"
     *     }
     * )
     *
     * @param int $id the answer id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $requestedAccount = $this->get('crv.repository.restricted_answer_repository')->findOneById($id);

        $this->getAnswerHandler()->delete($requestedAccount);

        return new View(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @return AnswerHandler
     */
    private function getAnswerHandler()
    {
        return $this->container->get('crv.handler.restricted_answer_handler');
    }
}