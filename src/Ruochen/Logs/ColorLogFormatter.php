<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/29
 * Time: 21:44
 */

namespace Ruochen\Logs;


use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class ColorLogFormatter extends LineFormatter
{

    private $colorMap ;

    /**
     * ColorLogFormatter constructor.
     * @param string $format
     * @param string $dateFormat
     * @param bool $allowInlineLineBreaks
     * @param bool $ignoreEmptyContextAndExtra
     */
    public function __construct($format = null, $dateFormat = null
        , $allowInlineLineBreaks = false, $ignoreEmptyContextAndExtra = false)
    {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
        $this->colorMap = [
            Logger::DEBUG     => chr(27) . '[32mDEBUG' . chr(27) . '[0m',
            Logger::INFO      => chr(27) . '[34mINFO' . chr(27) . '[0m',
            Logger::NOTICE    => chr(27) . '[37mNOTICE' . chr(27) . '[0m',
            Logger::WARNING   => chr(27) . '[33mWARNING' . chr(27) . '[0m',
            Logger::ERROR     => chr(27) . '[31mERROR' . chr(27) . '[0m',
            Logger::CRITICAL  => chr(27) . '[43mCRITICAL' . chr(27) . '[0m',
            Logger::ALERT     => chr(27) . '[41mALERT' . chr(27) . '[0m',
            Logger::EMERGENCY => chr(27) . '[46mEMERGENLogger' . chr(27) . '[0m',
        ];
    }

    public function format(array $record)
    {
        $record['level_name'] = $this->colorMap[$record['level']];
        return parent::format($record);
    }

}
