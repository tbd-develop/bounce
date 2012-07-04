<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terry
 * Date: 6/8/12
 * Time: 11:54 PM
 * To change this template use File | Settings | File Templates.
 */
class View
{
    public $ViewName;
    public $Controller;
    public $Model;

    public function __construct($controller, $name ) {
        $this->Controller = $controller;
        $this->ViewName = $name;
    }

    public function Render() {}
}
