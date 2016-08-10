<?php

namespace spec\AppBundle\Repository\Restricted;

use AppBundle\Repository\Doctrine\UserRepository;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class UserRepositorySpec extends ObjectBehavior
{
    private $userRepository;

    private $authorizationChecker;

    function let(UserRepository $userRepository, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->userRepository = $userRepository;
        $this->authorizationChecker = $authorizationChecker;

        $this->beConstructedWith($userRepository, $authorizationChecker);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Repository\Restricted\UserRepository');
        $this->shouldImplement('AppBundle\Repository\UserRepositoryInterface');
    }

    function it_can_find_logged_in_user_by_id(UserInterface $user)
    {
        $id = 1;

        $this->userRepository->find($id)->willReturn($user);
        $this->authorizationChecker->isGranted('view', $user)->willReturn(true);

        $this->find($id)->shouldReturn($user);
    }

    function it_cannot_find_other_user(UserInterface $user)
    {
        $id = 6;

        $this->userRepository->find($id)->willReturn($user);
        $this->authorizationChecker->isGranted('view', $user)->willReturn(false);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException')
            ->during('find', [$id]);
    }
}
