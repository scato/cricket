<?php

namespace spec\Cricket\CricketBundle\Reflection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReflectionClosureSpec extends ObjectBehavior
{
    function let()
    {
        $ref = 'REF';
        $val = 'VAL';

        $closure = function ($par) use (&$ref, $val)
        {
            return $par == $val;
        };

        $this->beConstructedWith($closure);
    }

    function it_fetches_the_source()
    {
        $this->getSource()->shouldReturn('return $par == $val;');
    }

    function it_gives_you_its_parameters()
    {
        $this->getParameters()->shouldReturn(array('par'));
    }

    function it_gives_you_its_bound_variables()
    {
        $this->getBoundVariables()->shouldReturn(array('ref', 'val'));
    }

    function it_gives_you_its_bound_values()
    {
        $this->getBoundValue('ref')->shouldReturn('REF');
        $this->getBoundValue('val')->shouldReturn('VAL');
    }
}
