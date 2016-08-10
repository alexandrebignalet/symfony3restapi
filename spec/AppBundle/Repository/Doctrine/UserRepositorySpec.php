<?php

namespace spec\AppBundle\Repository\Doctrine;

use AppBundle\Entity\Repository\UserEntityRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserRepositorySpec extends ObjectBehavior
{

    private $entityRepository;

    function let(UserEntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;

        $this->beConstructedWith($entityRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Repository\Doctrine\UserRepository');
        $this->shouldImplement('AppBundle\Repository\UserRepositoryInterface');
    }

    function it_can_find()
    {
        $id = 54;
        $this->find($id);
        $this->entityRepository->find($id)->shouldHaveBeenCalled();
    }
}
