# PHP 命令行脚本框架库

## 背景

部分业务使用PHP编写运维脚本, 但PHP缺乏一个易用的命令行程序开发库. 此框架的**基于注解的方式支持填空式**的快速开发命令行脚本. **并提供统一的日志|参数解析|控制台格式输出|帮助文档能力.**

## 使用方法

### 引入依赖

在运维脚本项目中引入依赖.

```
composer require hongye/rc-cmd
```

### 编写运维工具类

创建一个工具类比如SampleTool, 并继承`\Ruochen\Tools\CommandTool`, 分别在类和方法上加上如下注解.  最终通过调用 `SampleTool::getInstance()->process();`  使类文件变为脚本, 并为类文件加上可执行权限.

框架实现了一些易用的注解，基于注解快速定义命令、命令选项、命令参数.  由于PHP本身并不支持注解, 故注解需要添加在文档注释中.

可以为IDE添加注解支持

- Eclipse http://symfony.dubture.com/
- PhpStorm http://plugins.jetbrains.com/plugin/7320-php-annotations

#### @\Ruochen\Annotations\Command

此注解为工具定义一个命令，属性如下
- name 可选值，如不设值，则命令名称为方法名
- desc 可选值，命令的说明

#### @\Ruochen\Annotations\Operand
此注解为命令定义一个操作数，属性如下
- name 必须，操作数名称
- mode 模式，枚举值{"required","multiple","optional"}
    - required 表示此命令必须有此操作数
    - multiple 表示此操作数是多个值
    - optional 表示此操作数可选


#### @\Ruochen\Annotations\Option
此注解为命令定义一个选项。此注解可以注解在class或者command method上，如果注解在class上，则在所有command method上都可以访问到
- short 必须，选项缩写
- long 长选项
- mode 模式，枚举值
    - noArg 表示无参数，常用语开关选项
    - requiredArg 选项必须有参数 如：-f /var/mysqld.sock || --file=/var/mysqld.sock
    - optionalArg 选项可选参数
    - multipleArg 选项有多个参数


## 示例

命令工具文件 sample
``` php
#!/usr/bin/env php
<?php

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
}

$sampleTool = SampleTool::getInstance();

$sampleTool->process();
```

命令行提示
```bash
$ ./sample
Usage: ./sample <command> [options] [operands]

Options:
  -v, --version   Show version information and quit
  -h, --help      Show this help and quit
  -d, --database  select one database

Commands:
  query  query_xxx
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

```
$ ./sample query xxxx -d lvyahui
[2017-12-26 11:51:45] SampleTool.INFO: query.....lvyahui [] []
```

## 运行依赖

- php >= 7.0
- mbstring
- mcrypt
- hash
- composer
