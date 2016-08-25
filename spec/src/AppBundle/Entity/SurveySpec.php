<?php

namespace spec\src\AppBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SurveySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('src\AppBundle\Entity\Survey');
    }
}
