<?php

namespace Cricket\CricketBundle\Parser;

class Terminal extends Node
{
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function accept(NodeVisitor $visitor)
    {
        $visitor->visitTerminal($this);
    }
}

