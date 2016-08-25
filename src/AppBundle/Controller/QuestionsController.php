<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use AppBundle\Handler\QuestionHandler;
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
 * Class QuestionsController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("questions")
 */
class QuestionsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get a single Question.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Question",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerGroups={
     *   "questions_all",
     *   "answers_summary",
     *   "surveys_summary"
     * }, serializerEnableMaxDepthChecks=true)
     *
     * @param int   $questionId     the question id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($questionId)
    {
        $question = $this->getQuestionHandler()->get($questionId);

        $view = $this->view($question);

        return $view;
    }

    /**
     * Gets a collection of Questions if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Question",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerGroups={
     *   "questions_all",
     *   "answers_summary",
     *   "surveys_summary"
     * })
     *
     * @throws NotFoundHttpException when does not exist
     *
     *
     * @return View
     */
    public function cgetAction()
    {
        return $this->getQuestionHandler()->all(null, null);
    }

    /**
     * Creates a new Question by an Admin
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\QuestionType",
     *  output = "AppBundle\Entity\Question",
     *  statusCodes={
     *         201="Returned when a new Question has been successfully created",
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

            $question = $this->getQuestionHandler()->post($request->request->all());

            $routeOptions = [
                'questionId'  => $question->getId(),
                '_format'    => $request->get('_format'),
            ];

            return $this->routeRedirectView('get_questions', $routeOptions, Response::HTTP_CREATED);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing Question from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\QuestionType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the question id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id)
    {
        $requestedQuestion = $this->get('crv.repository.restricted_question_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $question \AppBundle\Entity\Question */
            $question = $this->getQuestionHandler()->patch(
                $requestedQuestion,
                $request->request->all()
            );

            $routeOptions = array(
                'questionId'        => $question->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_questions', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing Question from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\QuestionType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the question id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id)
    {
        $requestedAnswer = $this->get('crv.repository.restricted_question_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $answer \AppBundle\Entity\Question */
            $question = $this->getQuestionHandler()->put(
                $requestedAnswer,
                $request->request->all()
            );

            $routeOptions = array(
                'questionId'        => $question->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_questions', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Deletes a specific Question by ID
     *
     * @ApiDoc(
     *  description="Deletes an existing Question",
     *  statusCodes={
     *         204="Returned when an existing Question has been successfully deleted",
     *         403="Returned when trying to delete a non existent Question"
     *     }
     * )
     *
     * @param int $id the question id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $requestedAccount = $this->get('crv.repository.restricted_question_repository')->findOneById($id);

        $this->getQuestionHandler()->delete($requestedAccount);

        return new View(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @return QuestionHandler
     */
    private function getQuestionHandler()
    {
        return $this->container->get('crv.handler.restricted_question_handler');
    }
}