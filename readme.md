# PHP 命令行工具框架

## 示例

命令工具文件 sample
``` php
#!/usr/bin/env php
<?php

namespace Examples;
use League\CLImate\CLImate;
use Ruochen\Annotations\Command;
use Ruochen\Annotations\Desc;
use Ruochen\Annotations\Opread;
use Ruochen\Foundation\CommandTool;
use Ruochen\Helpers\ANSIHelper;

require_once __DIR__.'/../vendor/autoload.php';

class SampleTool extends CommandTool {

    /**
     * @Command
     * @Opread(name="key",mode="required")
     * @Desc("query some data")
     */
    public function query(){
        $this->logger->info("query.....");
    }

    /**
     * @Command(name="ll")
     * @Desc(value="list all data")
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

}

$sampleTool = new SampleTool(__FILE__);

$sampleTool->process();
```

命令行提示
```bash
$ ./sample
Usage: ./sample <command> [options] [operands]

Options:
  -v, --version  Show version information and quit
  -h, --help     Show this help and quit

Commands:
  query  query some data
  ll     list all data
  dp     dump all data
```

```
$ ./sample ll
Pid      Name         Stat
1996     crond        OK
1107     sshd         ERR
1993     rsyslogd     UNKOWN
```
## 依赖

- php >= 7.0
