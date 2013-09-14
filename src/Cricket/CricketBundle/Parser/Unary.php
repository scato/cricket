<?php

namespace Cricket\CricketBundle\Parser;

class Unary extends Node
{
    public $first;

    public function __construct($value, $first)
    {
        $this->value = $value;
        $this->first = $first;
    }

    public function accept(NodeVisitor $visitor)
    {
        $this->first->accept($visitor);

        $visitor->visitUnary($this);
    }
}

