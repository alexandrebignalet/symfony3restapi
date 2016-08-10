<?php

// /spec/AppBundle/Handler/UserHandlerSpec.php

namespace spec\AppBundle\Handler;

use AppBundle\Repository\UserRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserHandlerSpec extends ObjectBehavior
{
    private $repository;

    function let(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;

        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Handler\UserHandler');
        $this->shouldImplement('AppBundle\Handler\HandlerInterface');
    }

    function it_can_GET()
    {
        $id = 777;
        $this->get($id);
        $this->repository->find($id)->shouldHaveBeenCalled();
    }

    function it_cannot_get_ALL()
    {
        $this->shouldThrow('\DomainException')->during('all', [1,2]);
    }

    function it_cannot_POST()
    {
        $this->shouldThrow('\DomainException')->during('post', [['param1']]);
    }

    function it_cannot_PUT()
    {
        $this->shouldThrow('\DomainException')->during('put', [['param1'], []]);
    }

    function it_should_allow_PATCH(User $user)
    {
        // to be implemented
    }

    function it_should_throw_if_PATCH_not_given_a_valid_user()
    {
        // to be implemented
    }

    function it_cannot_DELETE()
    {
        $this->shouldThrow('\DomainException')->during('delete', ['something']);
    }
}