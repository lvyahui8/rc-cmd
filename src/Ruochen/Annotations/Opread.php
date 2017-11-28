<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 0:38
 */

namespace Ruochen\Annotations;


use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class Opread
 * @package Ruochen\Annotations
 * @Annotation
 * @Target({"METHOD"})
 */
class Opread
{
    /**
     * @var string
     * @Required()
     */
    public $name;

    /**
     * @Enum({"required","multiple","optional"})
     */
    public $mode;
}