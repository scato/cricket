<?php

namespace Cricket\CricketBundle\Parser;

use RuntimeException;

class PrattParser
{
    private $ignoreList = array();
    private $terminalTable = array();
    private $operatorTable = array();
    private $token;
    private $tokenNr;
    private $tokens;

    public function ignore($type)
    {
        $this->ignoreList[] = $type;

        return $this;
    }

    public function terminal($type, $class = 'Cricket\CricketBundle\Parser\Terminal')
    {
        $this->terminalTable[$type] = array(
            'id' => '(terminal)',
            'arity' => 'none',
            'lbp' => 0,
            'nud' => function ($token) use ($class) {
                return new $class($token['value']);
            },
            'led' => function () {
                throw new RuntimeException("Missing operator.");
            },
        );
        
        return $this;
    }

    private function operator($value, $bp, $def)
    {
        $this->operatorTable[$value] = $def + array(
            'id' => $value,
            'lbp' => $bp,
            'nud' => function () {
                throw new RuntimeException("Undefined.");
            },
            'led' => function () {
                throw new RuntimeException("Missing operator.");
            },
        );

        return $this;
    }

    public function unary($value, $bp, $class = 'Cricket\CricketBundle\Parser\Unary')
    {
        return $this->operator($value, $bp, array('nud' => function ($token) use ($value, $bp, $class) {
            return new $class(
                $value,
                $this->expression($bp)
            );
        }));
    }

    public function binary($value, $bp, $class = 'Cricket\CricketBundle\Parser\Binary')
    {
        return $this->operator($value, $bp, array('led' => function ($token, $left) use ($value, $bp, $class) {
            return new $class(
                $left,
                $value,
                $this->expression($bp)
            );
        }));
    }

    private function advance($id = null) {
        if ($id !== null && $this->token['id'] !== $id) {
            throw new RuntimeException("Expected '" . $id . "'");
        }

        if ($this->tokenNr >= count($this->tokens)) {
            $this->token = array('id' => '(end)', 'lbp' => 0);
            return;
        }

        $token = $this->tokens[$this->tokenNr];
        $this->tokenNr += 1;

        if (is_array($token)) {
            $type = $token[0];
            $value = $token[1];
        } else {
            $type = 0;
            $value = $token;
        }

        if (in_array($type, $this->ignoreList)) {
            $this->advance();
        } elseif (isset($this->terminalTable[$type])) {
            $this->token = $this->terminalTable[$type];
            $this->token['value'] = $value;
        } elseif (isset($this->operatorTable[$value])) {
            $this->token = $this->operatorTable[$value];
        } else {
            throw new RuntimeException("Unexpected token " . token_name($type) . ": '{$value}'");
        }
    }

    private function expression($rbp)
    {
        $token = $this->token;
        $this->advance();
        $left = $token['nud']($token);
        while ($rbp < $this->token['lbp']) {
            $token = $this->token;
            $this->advance();
            $left = $token['led']($token, $left);
        }
        return $left;
    }

    public function __construct()
    {
        $this
            ->terminal('(end)');
    }

    public function parse($source)
    {
        $this->tokens = token_get_all('<?php ' . $source);
        $this->tokenNr = 1; // skip '<?php' token

        $this->advance();

        $expression = $this->expression(0);

        $this->advance('(end)');

        return $expression;
    }
}
