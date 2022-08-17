<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Concerns\Phink\InteractsWithConfig;
use Phinx\Console\Command\Migrate;

class ModulePhinkMigrate extends Migrate
{
    use InteractsWithModule, InteractsWithConfig;

    protected static $defaultName = 'module:phink-migrate';
    protected static $defaultDescription = '执行所有迁移脚本';
}