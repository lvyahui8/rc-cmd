<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 21:36
 */

namespace Ruochen\Logs;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ToolLogger extends Logger
{

    private $logfile;

    /**
     * @param string             $name       The logging channel
     * @param HandlerInterface[] $handlers   Optional stack of handlers, the first one in the array is called first, etc.
     * @param callable[]         $processors Optional array of processors
     */
    public function __construct($name, array $handlers = array(), array $processors = array())
    {
        parent::__construct($name,$handlers,$processors);
    }


    /**
     * @param $name
     * @param array $handlers
     * @param array $processors
     * @return ToolLogger
     */
    public static function newInstance($name, array $handlers = array(), array $processors = array()){
        $logger = new ToolLogger($name,$handlers,$processors);
        $formatter = new ColorLogFormatter("[%datetime%]  %level_name%: %message%\n");
        $logger->logfile = runtime_storage_path().'/logs/'.$name.'.log';
        $fileHandler = new StreamHandler($logger->logfile,Logger::INFO);
        $consoleHandler = new StreamHandler('php://stdout',Logger::DEBUG);
        $fileHandler->setFormatter($formatter);
        $consoleHandler->setFormatter($formatter);
        $logger->pushHandler($fileHandler);
        $logger->pushHandler($consoleHandler);
        return $logger;
    }

    /**
     * @return mixed
     */
    public function getLogfile()
    {
        return $this->logfile;
    }

}