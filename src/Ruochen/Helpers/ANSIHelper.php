<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 21:47
 */

namespace Ruochen\Helpers;


class ANSIHelper
{
    const FG_BLACK      = 30;
    const FG_READ       = 31;
    const FG_GRENN      = 32;
    const FG_YELLOW     = 33;
    const FG_BLUE       = 34;
    const FG_PURPLE     = 35;
    const FG_DARK_GREEN = 36;
    const FG_WHITE      = 37;

    const BG_BLACK      = 40;
    const BG_READ       = 41;
    const BG_GRENN      = 42;
    const BG_YELLOW     = 43;
    const BG_BLUE       = 44;
    const BG_PURPLE     = 45;
    const BG_DARK_GREEN = 46;
    const BG_WHITE      = 47;

    /**
     * @param string $str
     * @param int $colorCode
     * @return string
     */
    public static function colorWrap($str,$colorCode){
        return chr(27)."[${colorCode}m$str".chr(27).'[0m';
    }
}