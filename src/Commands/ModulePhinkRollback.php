<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Phink\InteractsWithModule;
use Phinx\Console\Command\Rollback;

class ModulePhinkRollback extends Rollback
{
    use InteractsWithModule;

    protected static $defaultName = 'module:phink-rollback';
    protected static $defaultDescription = '回滚之前的迁移脚本';
}