<?php

namespace Cricket\CricketBundle\Parser;

abstract class Node
{
    public $value;

    abstract public function accept(NodeVisitor $visitor);
}
