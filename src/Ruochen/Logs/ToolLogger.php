<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 21:36
 */

namespace Ruochen\Logs;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
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


    public static function getInstance($name, array $handlers = array(), array $processors = array()){
        $logger = new ToolLogger($name,$handlers,$processors);
        $formatter = new ColorLogFormatter("[%datetime%]  %level_name%: %message%\n");
        $fileHandler = new StreamHandler(runtime_storage_path().'/logs/'.$name.'.log',Logger::INFO);
        $consoleHandler = new StreamHandler('php://stdout',Logger::DEBUG);
        $fileHandler->setFormatter($formatter);
        $consoleHandler->setFormatter($formatter);
        $logger->pushHandler($fileHandler);
        $logger->pushHandler($consoleHandler);
        return $logger;
    }
}