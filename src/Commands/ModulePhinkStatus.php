<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Concerns\Phink\InteractsWithConfig;
use Phinx\Console\Command\Status;

class ModulePhinkStatus extends Status
{
    use InteractsWithModule, InteractsWithConfig;

    protected static $defaultName = 'module:phink-status';
    protected static $defaultDescription = '打印所有迁移脚本和状态';
}