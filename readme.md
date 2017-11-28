# PHP 命令行工具框架

## 示例

命令工具文件 sample
``` php
#!/usr/bin/env php
<?php

namespace Examples;
require_once __DIR__.'/../vendor/autoload.php';

class SampleTool extends CommandTool {

    /**
     * @Option
     * @Opread
     * @Desc query some data
     */
    public function query(){
        $this->logger->info("query.....");
    }

    /**
     * @Option
     * @Desc list all data
     */
    public function list(){
        $this->logger->info("list.....");
    }

    /**
     * @Option dp
     * @Desc dump data
     */
    public function dump(){
        $this->logger->info("dump.....");
    }

}

$sampleTool = new SampleTool();

$sampleTool->process();
```

命令行提示
```bash

```
## 依赖

