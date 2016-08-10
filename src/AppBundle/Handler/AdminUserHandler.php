<?php

namespace AppBundle\Handler;

use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Entity\User;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\UserRepositoryInterface;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminUserHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $profileFormHandler;

    /**
     * @var FormHandlerInterface
     */
    private $registrationFormHandler;

    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * UserHandler constructor.
     * @param FormHandlerInterface $profileFormHandler
     * @param FormHandlerInterface $registrationFormHandler
     * @param UserRepositoryInterface $repository
     */
    public function __construct(FormHandlerInterface $profileFormHandler,FormHandlerInterface $registrationFormHandler, UserRepositoryInterface $repository)
    {
        $this->registrationFormHandler = $registrationFormHandler;
        $this->repository = $repository;
        $this->profileFormHandler = $profileFormHandler;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->repository->findOneById($id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function all($limit = null, $offset = null)
    {
        return $this->repository->findAll();
    }

    /**
     * @param array                 $parameters
     * @param array                 $options
     * @return UserInterface
     */
    public function post(array $parameters, array $options = [])
    {
        $user = $this->registrationFormHandler->handle(
            new User(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $this->repository->save($user);

        return $user;
    }

    /**
     * @param mixed $resource
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function put($resource, array $parameters, array $options)
    {
        throw new \DomainException('The method is not implemented');
    }

    /**
     * @param mixed $user
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function patch($user, array $parameters, array $options = [])
    {
        if ( ! $user instanceof UserInterface) {
            throw new \InvalidArgumentException('Not a valid User');
        }

        $user = $this->profileFormHandler->handle(
            $user,
            $parameters,
            Request::METHOD_PATCH,
            $options
        );

        $this->repository->save($user);

        return $user;
    }

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        $this->guardUserImplementsInterface($resource);

        $this->repository->delete($resource);

        return true;
    }

    /**
     * @param $user
     */
    private function guardUserImplementsInterface($user)
    {
        if (!$user instanceof UserInterface) {
            throw new \InvalidArgumentException('Expected passed User to implement UserInterface');
        }
    }
}
