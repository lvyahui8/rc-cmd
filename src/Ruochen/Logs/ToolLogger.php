<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 21:36
 */

namespace Ruochen\Logs;


use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

class ToolLogger extends Logger
{


    /**
     * @param string             $name       The logging channel
     * @param HandlerInterface[] $handlers   Optional stack of handlers, the first one in the array is called first, etc.
     * @param callable[]         $processors Optional array of processors
     */
    public function __construct($name, array $handlers = array(), array $processors = array())
    {
        parent::__construct($name,$handlers,$processors);
    }
}