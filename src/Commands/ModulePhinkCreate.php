<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Concerns\Phink\InteractsWithConfig;
use Phinx\Console\Command\Create;

class ModulePhinkCreate extends Create
{
    use InteractsWithModule, InteractsWithConfig;

    protected static $defaultName = 'module:phink-create';
    protected static $defaultDescription = '创建迁移脚本文件';

}