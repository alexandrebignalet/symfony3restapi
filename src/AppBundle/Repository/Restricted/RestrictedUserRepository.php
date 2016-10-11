<?php

namespace AppBundle\Repository\Restricted;

use AppBundle\Model\UserInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RestrictedUserRepository extends RestrictedRepository implements UserRepositoryInterface, RepositoryInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(
        UserRepositoryInterface $repository,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function save(UserInterface $user, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('view', $user);

        $this->repository->save($user);
    }

    public function delete(UserInterface $user, array $arguments = [])
    {
        $this->denyAccessUnlessGranted('edit', $user);

        $this->repository->delete($user);
    }

    public function findOneById($id)
    {
        $user = $this->repository->findOneById($id);

        $this->denyAccessUnlessGranted('view', $user);

        return $user;
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        $users = $this->repository->findAll();

        foreach ($users as $user){
            $this->denyAccessUnlessGranted('view', $user);
        }

        return $users;
    }

    /**
     * @return UserInterface
     */
    public function findCurrent()
    {
        $user = $this->repository->findCurrent();

        $this->denyAccessUnlessGranted('view', $user);

        return $user;
    }
}