<?php

/**
 *  @package    Whiteplanes
 *  @author     Takuya Katsurada <mail@nutcrack.io>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/whiteplanes/whiteplanes.php
 */
namespace Whiteplanes;

use Whiteplanes\Details\Command;

/**
 *  The Whiteplanes class.
 */
class Whiteplanes
{

    /**
     * @var array
     */
    private $commands = [];

    /**
     * Whiteplanes constructor.
     * @param $source
     */
    public function __construct($source)
    {
        $token = [];
        foreach (\str_split($source) as $character) {
            if (in_array(ord($character), self::getTokens())) {
                $token[] = $character;
            }
        }
        $code = \implode("", $token);

        foreach ($this->pause($code) as $command) {
            if ($command->name === Command::COMMAND_REGISTER || $command->name === Command::COMMAND_CALL) {
                $command->location = count($this->commands);
            }
            $this->commands[] = $command;
        }
    }

    /**
     * @param Contextable $context
     */
    public function run(Contextable $context)
    {
        $registers = array_filter($this->commands, function ($command) {
            return $command->name === Command::COMMAND_REGISTER;
        });
        foreach ($registers as $command) {
            $command->execute($context);
        }

        foreach ($this->progress($context) as $command) {
            $command->execute($context);
        }
    }

    /**
     * @param \Whiteplanes\Contextable $context
     * @return \Generator
     */
    protected function progress(Contextable $context)
    {
        while ($context->counter < count($this->commands)) {
            $command = $this->commands[$context->counter];
            if ($command->name === Command::COMMAND_REGISTER) {
                $context->counter += 1;
                continue;
            }
            yield $command;
            $context->counter += 1;
        }
    }

    /**
     * @param $source
     */
    protected static function pause($source)
    {
        $cursor = 0;
        $count = \strlen($source);
        while ($cursor < $count) {
            $code = \substr($source, $cursor);
            $command = Command::makeCommand($code);
            $cursor += $command->step;

            yield $command;
        }
    }

    /**
     * @return array
     */
    private static function getTokens()
    {
        return [ Command::TOKEN_SPACE, Command::TOKEN_TAB, Command::TOKEN_NEWLINE ];
    }
}
