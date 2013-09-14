<?php

namespace spec\Cricket\CricketBundle\Parser;

use Cricket\CricketBundle\Parser\Binary;
use Cricket\CricketBundle\Parser\Terminal;
use Cricket\CricketBundle\Parser\Unary;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PrattParserSpec extends ObjectBehavior
{
    function it_parses_terminals()
    {
        $this->terminal(T_LNUMBER);

        $this->parse('42')->shouldBeLike(new Terminal('42'));
    }

    function it_parses_unary_operators()
    {
        $this->terminal(T_LNUMBER);
        $this->unary('-', 0);

        $this->parse('-42')->shouldBeLike(new Unary('-', new Terminal('42')));
    }

    function it_parses_binary_operators()
    {
        $this->terminal(T_LNUMBER);
        $this->binary('+', 10);

        $this->parse('42+24')->shouldBeLike(new Binary(new Terminal('42'), '+', new Terminal('24')));
    }

    function it_ignores_whitespace()
    {
        $this->ignore(T_WHITESPACE);
        $this->terminal(T_LNUMBER);
        $this->binary('+', 10);

        $this->parse('42 + 24')->shouldBeLike(new Binary(new Terminal('42'), '+', new Terminal('24')));
    }

    function it_follows_operator_precedence()
    {
        $this->terminal(T_LNUMBER);
        $this->binary('+', 10);
        $this->binary('*', 20);

        $this->parse('1+2*3')->shouldBeLike(new Binary(new Terminal('1'), '+', new Binary(new Terminal('2'), '*', new Terminal('3'))));
    }
}
