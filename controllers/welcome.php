<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 11/7/13
 * Time: 7:09 AM
 */

class Welcome extends Controller {
    public function index() {
        return $this->View("index");
    }
} 