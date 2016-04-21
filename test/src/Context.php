<?php

/**
 *  @package    Whiteplanes/Test
 *  @author     Takuya Katsurada <mail@nutcrack.io>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/whiteplanes/whiteplanes.php
 */
namespace Whiteplanes\Test;

use Whiteplanes\Contextable;

/**
 * The Context class
 */
class Context extends \stdClass implements Contextable
{
    /**
     * @var     string
     */
    private $code = "";

    /**
     * Context constructor.
     */
    public function __construct()
    {
        $this->stack     = [];
        $this->heap      = [];
        $this->labels    = [];
        $this->callstack = [];
        $this->counter   = 0;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $name
     * @param $value
     */
    public function output($name, $value)
    {
        $this->code .= $value;
    }

    /**
     * @param $name
     * @return int|string
     */
    public function input($name)
    {
        return $name === "INPUT_CHAR" ? "H" : 72;
    }
}
