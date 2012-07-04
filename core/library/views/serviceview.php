<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terry
 * Date: 6/9/12
 * Time: 12:23 AM
 * To change this template use File | Settings | File Templates.
 */
abstract class ServiceView extends View implements IServiceView
{
    public $Controller;
    public $ResponseName;
    public $Type;

    function ResponseData()
    {
        return;
    }
}