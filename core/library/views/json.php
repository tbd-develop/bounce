<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terry
 * Date: 6/8/12
 * Time: 11:55 PM
 * To change this template use File | Settings | File Templates.
 */
class Json extends ServiceView
{
    private $_model;

    public function __construct($controller, $model) {
        $this->Controller = $controller;
        $this->_model = $model;
        $this->Type = "json";
    }

    public function TypeIs($type = "json") {
        $this->Type = $type;
    }

    public function ResponseData() {
        return $this->_model;
    }
}
