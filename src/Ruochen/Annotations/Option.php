<?php
/**
 * Created by PhpStorm.
 * User: lvyahui8@126.com
 * Date: 2017/12/6
 * Time: 16:15
 */

namespace Ruochen\Annotations;
use Doctrine\Common\Annotations\Annotation\Required;


/**
 * Class Desc
 * @package Ruochen\Annotations
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
class Option
{
    /**
     * @Required
     * @var string
     */
    public $short;

    /**
     * @var string
     */
    public $long;

    /**
     * @Enum({"noArg","requiredArg","optionalArg","multipleArg"})
     */
    public $mode;

    /**
     * @var string
     */
    public $desc;


    /**
     * @var string|array
     */
    public $default;
}