<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Phink\InteractsWithModule;
use Phinx\Console\Command\Status;

class ModulePhinkStatus extends Status
{
    use InteractsWithModule;

    protected static $defaultName = 'module:phink-status';
    protected static $defaultDescription = '打印所有迁移脚本和状态';
}