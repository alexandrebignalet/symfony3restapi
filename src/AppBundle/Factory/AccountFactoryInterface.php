<?php

namespace AppBundle\Factory;

use AppBundle\DTO\AccountDTO;
use AppBundle\Entity\Account;

interface AccountFactoryInterface
{
    /**
     * @param  string       $accountName
     * @return Account
     */
    public function create($accountName);

    /**
     * @param  AccountDTO   $accountDTO
     * @return Account
     */
    public function createFromDTO(AccountDTO $accountDTO);
}