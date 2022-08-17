<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Concerns\Phink\InteractsWithConfig;
use Phinx\Console\Command\SeedCreate;

class ModulePhinkSeedCreate extends SeedCreate
{
    use InteractsWithModule, InteractsWithConfig;

    protected static $defaultName = 'module:phink-seed-create';
    protected static $defaultDescription = '创建 seed 数据填充类';
}