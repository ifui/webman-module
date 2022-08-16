<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Phink\InteractsWithModule;
use Phinx\Console\Command\SeedRun;

class ModulePhinkSeedRun extends SeedRun
{
    use InteractsWithModule;

    protected static $defaultName = 'module:phink-seed-run';
    protected static $defaultDescription = '执行数据填充操作';
}