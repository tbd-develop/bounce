<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terry
 * Date: 6/10/12
 * Time: 7:50 AM
 * To change this template use File | Settings | File Templates.
 */
class Redirect implements IRedirect
{
    private $_location;

    public function __construct($location) {
        $this->_location = $location;
    }
    public function Render() {
        header( "Location: {$this->_location}" );
    }
}
