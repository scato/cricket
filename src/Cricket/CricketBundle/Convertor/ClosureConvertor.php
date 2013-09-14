<?php

namespace Cricket\CricketBundle\Convertor;

use Cricket\CricketBundle\Reflection\ReflectionClosure;
use Cricket\CricketBundle\Parser\ExpressionParser;
use RuntimeException;

class ClosureConvertor
{
    public function convert($closure)
    {
        $reflection = new ReflectionClosure($closure);
        $source = $reflection->getSource();

        if (!preg_match('/^return(.*);$/', $source)) {
            throw new RuntimeException("Unsupported closure: '$source'");
        }

        $source = trim(preg_replace('/^return(.*);$/', '\\1', $source));
        
        $parser = new ExpressionParser();
        $root = $parser->parse($source);

        $visitor = new ConvertorVisitor(
            $reflection->getParameters(),
            $reflection->getBoundVariables()
        );
        
        $root->accept($visitor);

        return $visitor->getResult();
    }
}
