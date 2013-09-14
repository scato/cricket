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
        $body = $reflection->getBody();

        if (!preg_match('/^return(.*);$/', $body)) {
            throw new RuntimeException("Unsupported closure: '$body'");
        }

        $body = trim(preg_replace('/^return(.*);$/', '\\1', $body));
        
        $parser = new ExpressionParser();
        $root = $parser->parse($body);

        $visitor = new ConvertorVisitor(
            $reflection->getParameters(),
            $reflection->getBoundVariables()
        );
        
        $root->accept($visitor);

        return $visitor->getResult();
    }
}
