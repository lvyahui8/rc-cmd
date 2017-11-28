# PHP 命令行工具框架

## 示例

命令工具文件 sample
``` php
#!/usr/bin/env php
<?php

namespace Examples;
use Ruochen\Annotations\Command;
use Ruochen\Annotations\Desc;
use Ruochen\Annotations\Opread;
use Ruochen\Foundation\CommandTool;

require_once __DIR__.'/../vendor/autoload.php';

class SampleTool extends CommandTool {

    /**
     * @Command
     * @Opread(name="key",mode="required")
     * @Desc(value="query some data")
     */
    public function query(){
        $this->logger->info("query.....");
    }

    /**
     * @Command
     * @Desc(value="list all data")
     */
    public function list(){
        $this->logger->info("list.....");
    }


    /**
     * @Command(name="dp")
     * @Desc(value="dump all data")
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
C:\soft\php7\php.exe E:\Work\rc-cmd\examples\sample
Usage: E:\Work\rc-cmd\examples\sample <command> [options] [operands]

Options:
  -v, --version  Show version information and quit
  -h, --help     Show this help and quit

Commands:
  query  query some data
  list   list all data
  dp     dump all data
```
## 依赖

