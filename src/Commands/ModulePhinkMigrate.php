<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Phink\InteractsWithModule;
use Phinx\Console\Command\Migrate;

class ModulePhinkMigrate extends Migrate
{
    use InteractsWithModule;

    protected static $defaultName = 'module:phink-migrate';
    protected static $defaultDescription = '执行 Phink 所有脚本';
}