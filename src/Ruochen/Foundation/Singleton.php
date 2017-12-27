<?php
/**
 * Created by PhpStorm.
 * User: lvyahui8@126.com
 * Date: 2017/12/6
 * Time: 16:05
 */

namespace Ruochen\Foundation;


trait Singleton
{
    protected static $instance;

    public static function getInstance()
    {
        return isset(self::$instance) ? self::$instance : self::$instance = new static;
    }
}