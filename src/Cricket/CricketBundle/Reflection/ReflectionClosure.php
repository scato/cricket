<?php

namespace Cricket\CricketBundle\Reflection;

use ReflectionFunction;
use RuntimeException;

class ReflectionClosure
{
    private $closure;

    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    public function getBody()
    {
        $meta = new ReflectionFunction($this->closure);

        $file = file_get_contents($meta->getFileName());
        $lines = explode("\n", str_replace("\r", '', $file));

        $offset = $meta->getStartLine() - 1;
        $length = $meta->getEndLine() - $offset;
        $body = array_slice($lines, $offset, $length);
        $body = implode("\n", $body);

        preg_match_all('/function[^{}]*\\{[^{}]*\\}/', $body, $matches);

        if (count($matches[0]) !== 1) {
            throw new RuntimeException("Source is invalid: '$body'");
        }

        $body = trim(preg_replace('/^function[^{}]*\\{([^{}]*)\\}$/', '\\1', $matches[0][0]));

        return $body;
    }

    public function getParameters()
    {
        $meta = new ReflectionFunction($this->closure);

        return array_map(function ($parameter) {
            return $parameter->name;
        }, $meta->getParameters());
    }

    public function getBoundVariables()
    {
        $meta = new ReflectionFunction($this->closure);

        return array_keys($meta->getStaticVariables());
    }

    public function getBoundValue($key)
    {
        $meta = new ReflectionFunction($this->closure);

        return $meta->getStaticVariables()[$key];
    }
}
