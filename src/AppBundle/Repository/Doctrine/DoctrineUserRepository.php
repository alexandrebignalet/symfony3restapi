<?php
namespace AppBundle\Repository\Doctrine;

use AppBundle\Model\UserInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\UserRepositoryInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DoctrineUserRepository implements UserRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * DoctrineUserRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     * @param UserManagerInterface $userManager
     */
    public function __construct(CommonDoctrineRepository $commonRepository, TokenStorageInterface $tokenStorage)
    {
        $this->commonRepository = $commonRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param  UserInterface        $user
     */
    public function refresh(UserInterface $user)
    {
        $this->commonRepository->refresh($user);
    }

    /**
     * @param   UserInterface       $user
     * @param   array               $arguments
     */
    public function save(UserInterface $user, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->save($user, $arguments);
    }

    /**
     * @param   UserInterface       $user
     * @param   array               $arguments
     */
    public function delete(UserInterface $user, array $arguments = ['flush'=>true])
    {
        $this->commonRepository->delete($user, $arguments);
    }

    public function findOneById($id)
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:User')->find($id);
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        return $this->commonRepository->getEntityManager()->getRepository('AppBundle:User')->findAll();
    }

    /**
     * @return UserInterface
     */
    public function findCurrent()
    {
        return $this->tokenStorage->getToken()->getUser();


    }
}