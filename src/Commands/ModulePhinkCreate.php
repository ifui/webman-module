<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Phink\InteractsWithModule;
use Phinx\Console\Command\Create;

class ModulePhinkCreate extends Create
{
    use InteractsWithModule;

    protected static $defaultName = 'module:phink-create';
    protected static $defaultDescription = '创建迁移脚本文件';

}