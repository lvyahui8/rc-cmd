<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/12/17
 * Time: 9:24
 */

namespace Ruochen\Tools;

use League\CLImate\CLImate;
use Ruochen\Annotations\Command;
use Ruochen\Annotations\Desc;
use Ruochen\Annotations\Operand;
use Ruochen\Annotations\Option;
use Ruochen\Foundation\CommandTool;
use Ruochen\Helpers\ANSIHelper;
/**
 * Class SampleTool
 * @package Examples
 * @Option(short="d",long="database",desc="select one database",mode="requiredArg")
 */
class SampleTool extends CommandTool {

    /**
     * @Command(desc="query_xxx")
     * @Operand(name="key",mode="required")
     * @Desc("query some data")
     */
    public function query(){
        $database = $this->getOption('d');
        $this->logger->info("query.....$database");
    }

    /**
     * @Command(name="ll")
     * @Desc(value="list all data")
     * @Option(short="f",long="filter",mode="requiredArg",desc="xxxxx")
     */
    public function list(){
        $cli = new CLImate();
        $outTables [] = [
            'Pid','Name','Stat',
        ];
        $outTables [] = [
            1996, 'crond', ANSIHelper::colorWrap('OK', ANSIHelper::FG_BLUE),
        ];
        $outTables [] = [
            1107, 'sshd', ANSIHelper::colorWrap('ERR', ANSIHelper::FG_READ),
        ];
        $outTables [] = [
            1993, 'rsyslogd', ANSIHelper::colorWrap('UNKOWN', ANSIHelper::FG_YELLOW),
        ];
        $cli->columns($outTables);
    }


    /**
     * @Command("dp")
     * @Desc("dump all data")
     */
    public function dump(){
        $this->logger->info("dump.....");
    }

    /**
     * @Command
     */
    public function env(){
        $val = env("sample_env");
        var_dump($val);
    }


    /**
     * @Command(name="set")
     * @Operand(name="key",mode="required")
     * @Operand(name="val",mode="required")
     * @Desc(value="To modify a variable's value, the default permanent")
     */
    public function setEnv(){
        $key = $this->getOperand('key');
        $value = $this->getOperand('val');
        if(! $value){
            $this->logger->warn("Value is empty");
        }

        $this->logger->info("$key => $value");
    }
}
