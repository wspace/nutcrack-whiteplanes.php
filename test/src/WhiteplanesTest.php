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
 * The Whiteplanes testcase
 */
class WhiteplanesTest extends \PHPUnit_Framework_TestCase
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
     *  Execute "HelloWorld.ws"
     *
     *  @access public
     *  @since  1.0.0
     */
    public function testHelloWorld()
    {
        $content = \file_get_contents('./test/etc/HelloWorld.ws');
        $interpreter = new Whiteplanes($content);
        $interpreter->run($this->context);

        $this->assertEquals($this->context->getCode(), "Hello World" . PHP_EOL);
    }

    /**
     *  Execute "HeapControl.ws"
     *
     *  @access public
     *  @since  1.0.0
     */
    public function testHeapControl()
    {
        $content = \file_get_contents('./test/etc/HeapControl.ws');
        $interpreter = new Whiteplanes($content);
        $interpreter->run($this->context);

        $this->assertEquals($this->context->getCode(), "Hello World" . PHP_EOL);
    }

    /**
     *  Execute "FlowControl.ws"
     *
     *  @access public
     *  @since  1.0.0
     */
    public function testFlowControl()
    {
        $content = \file_get_contents('./test/etc/FlowControl.ws');
        $interpreter = new Whiteplanes($content);
        $interpreter->run($this->context);

        $this->assertEquals($this->context->getCode(), "52");
    }

    /**
     *  Execute "Count.ws"
     *
     *  @access public
     *  @since  1.0.0
     */
    public function testCount()
    {
        $content = \file_get_contents('./test/etc/Count.ws');
        $interpreter = new Whiteplanes($content);
        $interpreter->run($this->context);

        $this->assertEquals($this->context->getCode(), "1\n2\n3\n4\n5\n6\n7\n8\n9\n10\n");
    }

    /**
     *  Execute "Input.ws"
     *
     *  @access public
     *  @since  1.0.0
     */
    public function testInput()
    {
        $content = \file_get_contents('./test/etc/Input.ws');
        $interpreter = new Whiteplanes($content);
        $interpreter->run($this->context);

        $this->assertEquals($this->context->getCode(), "H72");
    }
}
