<?php

namespace AppBundle\Repository;

use AppBundle\Model\UserInterface;

/**
 * Interface UserRepositoryInterface
 * @package AppBundle\Repository
 */
interface UserRepositoryInterface
{
    /**
     * @param UserInterface         $user
     * @param array                 $arguments
     */
    public function save(UserInterface $user, array $arguments = ['flush'=>true]);

    /**
     * @param UserInterface         $user
     * @param array                 $arguments
     */
    public function delete(UserInterface $user, array $arguments = ['flush'=>true]);

    /**
     * @param                       $id
     * @return                      mixed|null
     */
    public function findOneById($id);

    /**
     * @return mixed
     */
    public function findAll();
}