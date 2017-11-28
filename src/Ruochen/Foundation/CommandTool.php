<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/28
 * Time: 23:40
 */

namespace Ruochen\Foundation;


use GetOpt\GetOpt;
use JBZoo\Utils\FS;
use Monolog\Logger;

class CommandTool extends GetOpt
{
    /**
     * @var Logger
     */
    protected $logger;

    protected $toolfile;

    /**
     * CommandTool constructor.
     * @param string $toolfile
     * @param array|string $options
     * @param array $settings
     */
    public function __construct($toolfile, $options=null, $settings = [])
    {
        parent::__construct($options,$settings);
        $this->toolfile = $toolfile;
        $this->init();
    }

    private function init()
    {
        $this->initLogger();
    }

    private function initLogger()
    {
        $this->logger = new Logger((FS::filename($this->toolfile)));
    }


}