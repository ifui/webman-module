<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Concerns\Phink\InteractsWithConfig;
use Phinx\Console\Command\Rollback;

class ModulePhinkRollback extends Rollback
{
    use InteractsWithModule, InteractsWithConfig;

    protected static $defaultName = 'module:phink-rollback';
    protected static $defaultDescription = '回滚之前的迁移脚本';
}