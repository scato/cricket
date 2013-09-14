<?php

namespace spec\Cricket\CricketBundle\Convertor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClosureConvertorSpec extends ObjectBehavior
{
    function it_converts_parameters()
    {
        $this->convert(function ($user) {
            return $user;
        })->shouldReturn('user');
    }

    function it_converts_bound_variables()
    {
        $user = 'foo';

        $this->convert(function () use ($user) {
            return $user;
        })->shouldReturn(':user');
    }

    function it_converts_literals()
    {
        $this->convert(function () {
            return 42;
        })->shouldReturn('42');
    }

    function it_converts_minus()
    {
        $this->convert(function () {
            return -42;
        })->shouldReturn('(- 42)');
    }

    function it_converts_equals()
    {
        $user = 'foo';

        $this->convert(function ($u) use ($user) {
            return $u == $user;
        })->shouldReturn('(u = :user)');
    }
}
