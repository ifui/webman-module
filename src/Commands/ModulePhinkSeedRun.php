<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Concerns\Phink\InteractsWithConfig;
use Phinx\Console\Command\SeedRun;

class ModulePhinkSeedRun extends SeedRun
{
    use InteractsWithModule, InteractsWithConfig;

    protected static $defaultName = 'module:phink-seed-run';
    protected static $defaultDescription = '执行数据填充操作';
}