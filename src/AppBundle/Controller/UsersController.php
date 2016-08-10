<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
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
 * Class UsersController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("users")
 */
class UsersController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get a single User.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param int   $userId     the user id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($userId)
    {
        $user = $this->getUserHandler()->get($userId);

        $view = $this->view($user);

        return $view;
    }

    /**
     * Gets a collection of Users if Admin.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @Annotations\View(serializerGroups={
     *   "users_all",
     *   "accounts_summary"
     * })
     *
     * @throws NotFoundHttpException when does not exist
     *
     *
     * @return View
     */
    public function cgetAction()
    {
        return $this->getAdminUserHandler()->all();
    }

    /**
     * Creates a new User by an Admin
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\UserFormType",
     *  output = "AppBundle\Entity\User",
     *  statusCodes={
     *         201="Returned when a new User has been successfully created",
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

            $user = $this->getAdminUserHandler()->post($request->request->all());

            $routeOptions = [
                'userId'  => $user->getId(),
                '_format'    => $request->get('_format'),
            ];

            return $this->routeRedirectView('get_users', $routeOptions, Response::HTTP_CREATED);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Update existing User from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\UserType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     401 = "Returned when provided password is incorrect",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request   $request    the request object
     * @param int       $id         the user id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id)
    {
        $requestedUser = $this->get('crv.repository.restricted_user_repository')->findOneById($id);
        try {

            $statusCode = Response::HTTP_NO_CONTENT;

            /** @var $user \AppBundle\Entity\User */
            $user = $this->getUserHandler()->patch(
                $requestedUser,
                $request->request->all()
            );

            $routeOptions = array(
                'userId'        => $user->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_users', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {

            return $e->getForm();
        }
    }

    /**
     * Deletes a specific User by ID
     *
     * @ApiDoc(
     *  description="Deletes an existing User",
     *  statusCodes={
     *         204="Returned when an existing User has been successfully deleted",
     *         403="Returned when trying to delete a non existent User"
     *     }
     * )
     *
     * @param int $id the user id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $requestedAccount = $this->get('crv.repository.restricted_user_repository')->findOneById($id);

        $this->getAdminUserHandler()->delete($requestedAccount);

        return new View(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @return UserHandler
     */
    private function getUserHandler()
    {
        return $this->container->get('crv.handler.restricted_user_handler');
    }

    /**
     * @return AdminUserHandler
     */
    private function getAdminUserHandler()
    {
        return $this->container->get('crv.handler.admin_user_handler');
    }
}