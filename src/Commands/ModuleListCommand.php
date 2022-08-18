<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Module;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleListCommand extends Command
{
    protected static $defaultName = 'module:list';
    protected static $defaultDescription = '显示应用状态列表';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $headers = [
            'name',
            'author',
            'version',
            'status'
        ];
        $rows = [];
        $modules = Module::getInstance()->getApplications();
        foreach ($modules as $module) {
            $rows[] = [
                $module['name'],
                $module['author'],
                $module['version'],
                $module['activity'] ? "<fg=green>true</>" : '<fg=red>false</>',
            ];
        }
        $this->table($headers, $rows);
        return self::SUCCESS;
    }
}