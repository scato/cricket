<?php

namespace Cricket\CricketBundle\Parser;

use RuntimeException;

class ExpressionParser extends PrattParser
{
    private $bps = array(
        //10 => array('?'),
        20 => array('||'),
        30 => array('&&'),
        40 => array('==', '===', '!=', '!=='),
        50 => array('<', '<=', '>=', '>'),
        60 => array('+', '-', '.'),
        70 => array('*', '/', '%'),
        80 => array('!', '-', 'new'),
        90 => array('->'),
    );

    private $types = array(
        //10 => 'ternary',
        20 => 'binary',
        30 => 'binary',
        40 => 'binary',
        50 => 'binary',
        60 => 'binary',
        70 => 'binary',
        80 => 'unary',
        90 => 'binary',
    );

    public function __construct()
    {
        $this
            ->ignore(T_WHITESPACE)
            ->ignore(T_COMMENT)
            ->terminal(T_LNUMBER)
            ->terminal(T_DNUMBER)
            ->terminal(T_STRING)
            ->terminal(T_CONSTANT_ENCAPSED_STRING)
            ->terminal(T_VARIABLE);

        foreach ($this->bps as $bp => $ids) {
            foreach ($ids as $id) {
                $this->{$this->types[$bp]}($id, $bp);
            }
        }
    }
}
