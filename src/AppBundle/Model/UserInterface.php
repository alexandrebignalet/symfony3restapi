<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 26/07/2016
 * Time: 10:16
 */

namespace AppBundle\Model;

use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    /**
     * @param AccountInterface $accountInterface
     * @return User
     */
    public function addAccount(AccountInterface $accountInterface);

    /**
     * @param AccountInterface $accountInterface
     * @return User
     */
    public function removeAccount(AccountInterface $accountInterface);

    /**
     * @param AccountInterface $accountInterface
     * @return bool
     */
    public function hasAccount(AccountInterface $accountInterface);

    /**
     * @return Collection
     */
    public function getAccounts();
}