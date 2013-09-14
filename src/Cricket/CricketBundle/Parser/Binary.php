<?php

namespace Cricket\CricketBundle\Parser;

class Binary extends Node
{
    public $first;
    public $second;

    public function __construct($first, $value, $second)
    {
        $this->first = $first;
        $this->value = $value;
        $this->second = $second;
    }

    public function accept(NodeVisitor $visitor)
    {
        $this->first->accept($visitor);
        $this->second->accept($visitor);

        $visitor->visitBinary($this);
    }
}

