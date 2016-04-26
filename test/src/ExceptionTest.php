<?php

/**
 *  @package    Whiteplanes/Test
 *  @author     Takuya Katsurada <mail@nutcrack.io>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/whiteplanes/whiteplanes.php
 */
namespace Whiteplanes\Test;

use Whiteplanes\Whiteplanes;

/**
 * The Exception testcase
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *  @var object $context
     */
    protected $context = null;

    /**
     *  Setup for the testcase.
     *
     *  @access protected
     *  @since  1.0.0
     */
    protected function setUp()
    {
        $this->context = new Context();
    }

    /**
     *  Throw 'invalidArgumentException'
     *
     *  @param $code
     *  @dataProvider invalidCodeProvider
     *  @expectedException \InvalidArgumentException
     *  @since  1.0.0
     */
    public function testThrowException($code)
    {
        $interpreter = new Whiteplanes($code);
    }

    /**
     *  Data provider for `testThrowException`
     */
    public function invalidCodeProvider()
    {
        return [[" ", "\t", "  \t\t", " \t\t", "\t \t\n", "\t \n",
            "\t\t\n", "\n\n ", "\n\n\t", "\t\n \n", "\t\n\t\n", "\t\n\n"]];
    }
}
