<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Phink\InteractsWithModule;
use Phinx\Console\Command\SeedCreate;

class ModulePhinkSeedCreate extends SeedCreate
{
    use InteractsWithModule;

    protected static $defaultName = 'module:phink-seed-create';
    protected static $defaultDescription = '创建 seed 数据填充类';
}