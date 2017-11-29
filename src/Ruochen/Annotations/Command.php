<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/28
 * Time: 23:44
 */

namespace Ruochen\Annotations;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Option
 * @package Ruochen\Annotations
 * @Annotation
 * @Target({"METHOD"})
 */
class Command
{
    /**
     * @var string
     */
    public $name;

    /**
     * Command constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        if(isset($values['value'])) $this->name = $values['value'];

        if(isset($values['name'])) $this->name = $values['name'];
    }


}