<?php

/**
 *  @package    Whiteplanes\Details
 *  @author     Takuya Katsurada <mail@nutcrack.io>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/whiteplanes/whiteplanes.php
 */

namespace Whiteplanes\Details;

use Whiteplanes\Contextable;

/**
 *  The Command class.
 */
class Command extends \stdClass
{
    // Character.
    const TOKEN_SPACE   = 32;
    const TOKEN_TAB     = 9;
    const TOKEN_NEWLINE = 10;

    // Token.
    const COMMAND_PUSH        = "  ";
    const COMMAND_COPY        = " \t ";
    const COMMAND_SLIDE       = " \t\n";
    const COMMAND_DUPLICATE   = " \n ";
    const COMMAND_SWAP        = " \n\t";
    const COMMAND_DISCARD     = " \n\n";
    const COMMAND_ADD         = "\t   ";
    const COMMAND_SUB         = "\t  \t";
    const COMMAND_MUL         = "\t  \n";
    const COMMAND_DIV         = "\t \t ";
    const COMMAND_MOD         = "\t \t\t";
    const COMMAND_STORE       = "\t\t ";
    const COMMAND_RETRIEVE    = "\t\t\t";
    const COMMAND_REGISTER    = "\n  ";
    const COMMAND_CALL        = "\n \t";
    const COMMAND_JUMP        = "\n \n";
    const COMMAND_TEST_EQUAL  = "\n\t ";
    const COMMAND_TEST_LESS   = "\n\t\t";
    const COMMAND_RETURN      = "\n\t\n";
    const COMMAND_END         = "\n\n\n";
    const COMMAND_OUTPUT_CHAR = "\t\n  ";
    const COMMAND_OUTPUT_INT  = "\t\n \t";
    const COMMAND_INPUT_CHAR  = "\t\n\t ";
    const COMMAND_INPUT_INT   = "\t\n\t\t";

    /**
     * @var array
     */
    private static $behaviors = [
        Command::COMMAND_PUSH        => ['name' => 'PUSH',        'process' => 'push'],
        Command::COMMAND_COPY        => ['name' => 'COPY',        'process' => 'copy'],
        Command::COMMAND_SLIDE       => ['name' => 'SLIDE',       'process' => 'slide'],
        Command::COMMAND_DUPLICATE   => ['name' => 'DUPLICATE',   'process' => 'duplicate'],
        Command::COMMAND_SWAP        => ['name' => 'SWAP',        'process' => 'swap'],
        Command::COMMAND_DISCARD     => ['name' => 'DISCARD',     'process' => 'discard'],
        Command::COMMAND_ADD         => ['name' => 'ADD',         'process' => 'add'],
        Command::COMMAND_SUB         => ['name' => 'SUB',         'process' => 'sub'],
        Command::COMMAND_MUL         => ['name' => 'MUL',         'process' => 'mul'],
        Command::COMMAND_DIV         => ['name' => 'DIV',         'process' => 'div'],
        Command::COMMAND_MOD         => ['name' => 'MOD',         'process' => 'mod'],
        Command::COMMAND_STORE       => ['name' => 'STORE',       'process' => 'store'],
        Command::COMMAND_RETRIEVE    => ['name' => 'RETRIEVE',    'process' => 'retrieve'],
        Command::COMMAND_REGISTER    => ['name' => 'REGISTER',    'process' => 'register'],
        Command::COMMAND_CALL        => ['name' => 'CALL',        'process' => 'call'],
        Command::COMMAND_JUMP        => ['name' => 'JUMP',        'process' => 'jump'],
        Command::COMMAND_TEST_EQUAL  => ['name' => 'TEST_EQUAL',  'process' => 'test'],
        Command::COMMAND_TEST_LESS   => ['name' => 'TEST_LESS',   'process' => 'test'],
        Command::COMMAND_RETURN      => ['name' => 'RETURN',      'process' => 'back'],
        Command::COMMAND_END         => ['name' => 'END',         'process' => 'end'],
        Command::COMMAND_OUTPUT_CHAR => ['name' => 'OUTPUT_CHAR', 'process' => 'output'],
        Command::COMMAND_OUTPUT_INT  => ['name' => 'OUTPUT_INT',  'process' => 'output'],
        Command::COMMAND_INPUT_CHAR  => ['name' => 'INPUT_CHAR',  'process' => 'input'],
        Command::COMMAND_INPUT_INT   => ['name' => 'INPUT_INT',   'process' => 'input']
    ];

    /**
     *  Command constructor.
     *  @param  $name
     *  @param  $isParamRequired
     *  @return object
     *  @since  1.0.0
     */
    protected function __construct($name, $parameter)
    {
        $this->name = $name;
        $this->step = strlen($name) + strlen($parameter);
        $this->parameter = $parameter;
        $this->behavior = self::$behaviors[$name];
    }

    /**
     *  Execute.
     *  @param  Contextable $context
     *  @since  1.0.0
     */
    public function execute(Contextable $context)
    {
        $process = $this->behavior["process"];
        $this->$process($context);
    }

    /**
     *  Make a command instance.
     *  @param   $source
     *  @return  object
     *  @since   1.0.0
     */
    public static function makeCommand($source)
    {
        $match = [];
        foreach (static::getCommandList() as $name => $isParamRequired) {
            $pattern = '/(^' . $name . ')' . ($isParamRequired ? '([\s]*)' : '()') . '/';
            \preg_match($pattern, $source, $match);

            if (!empty($match)) {
                $parameter = self::getCommandParameter($match[2]);
                return new Command($name, $parameter);
            }
        }
        throw new \InvalidArgumentException("Invalid command");
    }

    /**
     *  PUSH command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function push(Contextable $context)
    {
        $value = intval($this->parameter, 2);
        array_push($context->stack, $value);
    }

    /**
     *  COPY command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function copy(Contextable $context)
    {
        $index = intval($this->parameter, 2);
        array_push($context->stack, $context->stack[$index]);
    }

    /**
     *  SLIDE command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function slide(Contextable $context)
    {
        $value = array_pop($context->stack);
        foreach (\range(0, intval($this->parameter, 2)) as $index) {
            array_pop($context->stack);
        }
        array_push($context->stack, $value);
    }

    /**
     *  DUPLICATE command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function duplicate(Contextable $context)
    {
        $index = count($context->stack) - 1;
        array_push($context->stack, $context->stack[$index]);
    }

    /**
     *  SWAP command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function swap(Contextable $context)
    {
        $lhs = array_pop($context->stack);
        $rhs = array_pop($context->stack);
        array_push($context->stack, $lhs);
        array_push($context->stack, $rhs);
    }

    /**
     *  DISCARD command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function discard(Contextable $context)
    {
        array_pop($context->stack);
    }

    /**
     *  ADD command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function add(Contextable $context)
    {
        $lhs = array_pop($context->stack);
        $rhs = array_pop($context->stack);
        array_push($context->stack, $lhs + $rhs);
    }

    /**
     *  SUB command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function sub(Contextable $context)
    {
        $lhs = array_pop($context->stack);
        $rhs = array_pop($context->stack);
        array_push($context->stack, $lhs - $rhs);
    }

    /**
     *  MUL command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function mul(Contextable $context)
    {
        $lhs = array_pop($context->stack);
        $rhs = array_pop($context->stack);
        array_push($context->stack, $lhs * $rhs);
    }

    /**
     *  DIV command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function div(Contextable $context)
    {
        $lhs = array_pop($context->stack);
        $rhs = array_pop($context->stack);
        array_push($context->stack, $lhs / $rhs);
    }

    /**
     *  MOD command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function mod(Contextable $context)
    {
        $lhs = array_pop($context->stack);
        $rhs = array_pop($context->stack);
        array_push($context->stack, $lhs % $rhs);
    }

    /**
     *  STORE command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function store(Contextable $context)
    {
        $value = array_pop($context->stack);
        $address = array_pop($context->stack);
        $context->heap[$address] = $value;
    }

    /**
     *  RETRIEVE command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function retrieve(Contextable $context)
    {
        $address = array_pop($context->stack);
        array_push($context->stack, $context->heap[$address]);
    }

    /**
     *  REGISTER command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function register(Contextable $context)
    {
        $context->labels[$this->parameter] = $this->location;
    }

    /**
     *  CALL command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function call(Contextable $context)
    {
        array_push($context->callstack, $this->location);
        $context->counter = $context->labels[$this->parameter];
    }

    /**
     *  JUMP command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function jump(Contextable $context)
    {
        $context->counter = $context->labels[$this->parameter];
    }

    /**
     *  TEST[EQUAL/LESS] command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function test(Contextable $context)
    {
        $value = array_pop($context->stack);
        $isEqual = $this->behavior["name"] === 'TEST_EQUAL';
        if (($isEqual && $value == 0) || (!$isEqual && $value < 0)) {
            $context->counter = $context->labels[$this->parameter];
        }
    }

    /**
     *  RETURN command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function back(Contextable $context)
    {
        $context->counter = array_pop($context->callstack);
    }

    /**
     *  END command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function end(Contextable $context)
    {
        $context->counter = PHP_INT_MAX - 1;
    }

    /**
     *  INPUT[CHAR/INT] command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function input(Contextable $context)
    {
        $isCharacter = $this->behavior["name"] === 'INPUT_CHAR';
        $address = array_pop($context->stack);
        $ascii = $context->input($this->behavior["name"]);
        $context->heap[$address] = $isCharacter ? ord($ascii) : $ascii;
    }

    /**
     *  OUTPUT[CHAR/INT] command.
     *  @param   Contextable $context
     *  @return  void
     *  @since   1.0.0
     */
    protected function output(Contextable $context)
    {
        $isCharacter = $this->behavior["name"] === 'OUTPUT_CHAR';
        $ascii = array_pop($context->stack);
        $context->output($this->behavior["name"], $isCharacter ? chr($ascii) : $ascii);
    }

    /**
     *  Get a command parameter.
     *  @param $source
     *  @return string
     *  @since 1.0.0
     */
    private static function getCommandParameter($source)
    {
        $parameter = "";
        foreach (\str_split($source) as $character) {
            $ascii = ord($character);
            if ($ascii === Command::TOKEN_SPACE) {
                $parameter .= "0";
            } elseif ($ascii === Command::TOKEN_TAB) {
                $parameter .= "1";
            } elseif ($ascii === Command::TOKEN_NEWLINE) {
                return $parameter . "\n";
            }
        }
    }

    /**
     *  Get command list.
     *  @return array
     *  @since 1.0.0
     */
    protected static function getCommandList()
    {
        return [
            Command::COMMAND_PUSH           => true,
            Command::COMMAND_COPY           => true,
            Command::COMMAND_SLIDE          => true,
            Command::COMMAND_DUPLICATE      => false,
            Command::COMMAND_SWAP           => false,
            Command::COMMAND_DISCARD        => false,
            Command::COMMAND_ADD            => false,
            Command::COMMAND_SUB            => false,
            Command::COMMAND_MUL            => false,
            Command::COMMAND_DIV            => false,
            Command::COMMAND_MOD            => false,
            Command::COMMAND_STORE          => false,
            Command::COMMAND_RETRIEVE       => false,
            Command::COMMAND_REGISTER       => true,
            Command::COMMAND_CALL           => true,
            Command::COMMAND_JUMP           => true,
            Command::COMMAND_TEST_EQUAL     => true,
            Command::COMMAND_TEST_LESS      => true,
            Command::COMMAND_RETURN         => false,
            Command::COMMAND_END            => false,
            Command::COMMAND_OUTPUT_CHAR    => false,
            Command::COMMAND_OUTPUT_INT     => false,
            Command::COMMAND_INPUT_CHAR     => false,
            Command::COMMAND_INPUT_INT      => false
        ];
    }
}
