<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/12/27
 * Time: 22:15
 */

namespace Ruochen\Foundation;


class CommandContext
{
    use Singleton;


    private $basePath;

    /**
     * CommandContext constructor.
     */
    public function __construct()
    {
        $this->setBasePath(getcwd());
    }

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param mixed $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = realpath($basePath);
    }


}