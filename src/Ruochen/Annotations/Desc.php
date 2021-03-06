<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 0:30
 */

namespace Ruochen\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class Desc
 * @package Ruochen\Annotations
 * @Annotation
 * @Target({"METHOD"})
 */
class Desc
{
    /**
     * @var string
     * @Required
     */
    public $value;

    /**
     * Desc constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
//        $this->value = $values['vaule'];
        if(isset($values['value'])){
            $this->value = $values['value'];
        }
    }


}