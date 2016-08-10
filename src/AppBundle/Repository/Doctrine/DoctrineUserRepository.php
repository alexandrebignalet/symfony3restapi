<?php
namespace AppBundle\Repository\Doctrine;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\RepositoryInterface;
use AppBundle\Repository\UserRepositoryInterface;

class DoctrineUserRepository implements UserRepositoryInterface, RepositoryInterface
{

    /**
     * @var CommonDoctrineRepository
     */
    private $commonRepository;

    /**
     * DoctrineUserRepository constructor.
     * @param CommonDoctrineRepository $commonRepository
     */
    public function __construct(CommonDoctrineRepository $commonRepository)
    {
        $this->commonRepository = $commonRepository;
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
}