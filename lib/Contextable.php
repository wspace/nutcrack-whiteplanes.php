<?php

/**
 *  @package    Whiteplanes
 *  @author     Takuya Katsurada <mail@nutcrack.io>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/whiteplanes/whiteplanes.php
 */
namespace Whiteplanes;

/**
 *  The Contextable interface.
 */
interface Contextable
{
    /**
     * @param $name
     * @return mixed
     */
    public function input($name);

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function output($name, $value);
}
