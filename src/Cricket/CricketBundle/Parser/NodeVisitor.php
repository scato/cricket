<?php

namespace Cricket\CricketBundle\Parser;

interface NodeVisitor
{
    public function visitTerminal(Terminal $node);
    public function visitUnary(Unary $node);
    public function visitBinary(Binary $node);
}
