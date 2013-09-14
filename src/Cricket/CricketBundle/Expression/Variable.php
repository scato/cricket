<?php

namespace Cricket\CricketBundle\Expression;

class Variable
{
    public $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
}

