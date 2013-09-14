<?php

namespace Cricket\CricketBundle\Convertor;

use Cricket\CricketBundle\Parser\Binary;
use Cricket\CricketBundle\Parser\NodeVisitor;
use Cricket\CricketBundle\Parser\Terminal;
use Cricket\CricketBundle\Parser\Unary;

class ConvertorVisitor implements NodeVisitor
{
    private $stack = array();
    private $parameters;
    private $variables;

    public function __construct($parameters, $variables)
    {
        $this->parameters = $parameters;
        $this->variables = $variables;
    }

    public function visitTerminal(Terminal $node)
    {
        $id = str_replace('$', '', $node->value);

        if (preg_match('/^\\$/', $node->value)) {
            if (in_array($id, $this->parameters)) {
                $expr = $id;
            } elseif (in_array($id, $this->variables)) {
                $expr = ":$id";
            }
        } else {
            $expr = $node->value;
        }

        array_push($this->stack, $expr);
    }

    public function visitUnary(Unary $node)
    {
        $first = array_pop($this->stack);
        $op = $node->value;

        $expr = "($op $first)";

        array_push($this->stack, $expr);
    }

    public function visitBinary(Binary $node)
    {
        $second = array_pop($this->stack);
        $first = array_pop($this->stack);

        if ($node->value === '==') {
            $expr = "($first = $second)";
        } else {
            $op = $node->value;
            $expr = "($first $op $second)";
        }

        array_push($this->stack, $expr);
    }

    public function getResult()
    {
        return array_pop($this->stack);
    }
}

