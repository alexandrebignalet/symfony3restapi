<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserSetupContext implements Context, SnippetAcceptingContext
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * UserContext constructor.
     * @param UserManagerInterface $userManager
     * @param EntityManagerInterface $em
     */
    public function __construct(UserManagerInterface $userManager, EntityManagerInterface $em)
    {
        $this->userManager = $userManager;
        $this->em = $em;
    }

    /**
     * @Given there are users with the following details:
     * @param TableNode $users
     */
    public function thereAreUsersWithTheFollowingDetails(TableNode $users)
    {

        foreach ($users->getColumnsHash() as $key => $val) {

            $user = $this->userManager->createUser();

            $user->setEnabled(true);
            $user->setUsername($val['username']);
            $user->setEmail($val['email']);
            $user->setPlainPassword($val['password']);

            $this->userManager->updateUser($user, true);

            $qb = $this->em->createQueryBuilder();

            $query = $qb->update('AppBundle:User', 'u')
                ->set('u.id', $qb->expr()->literal($val['uid']))
                ->where('u.username = :username')
                ->andWhere('u.email = :email')
                ->setParameters([
                    'username' => $val['username'],
                    'email' => $val['email']
                ])
                ->getQuery()
            ;

            $query->execute();
        }
    }
}