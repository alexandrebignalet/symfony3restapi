<?php

namespace AppBundle\Handler;

use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;

    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * UserHandler constructor.
     * @param FormHandlerInterface $formHandler
     * @param UserRepositoryInterface $repository
     */
    public function __construct(FormHandlerInterface $formHandler, UserRepositoryInterface $repository)
    {
        $this->formHandler = $formHandler;
        $this->repository = $repository;
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
    public function all($limit, $offset)
    {
        throw new \DomainException('The method is not implemented');
    }

    /**
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function post(array $parameters, array $options)
    {
        throw new \DomainException('The method is not implemented');
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

        $user = $this->formHandler->handle(
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
        throw new \DomainException('The method is not implemented yet');
    }
}
