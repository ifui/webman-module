<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Module;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleStop extends Command
{
    protected static $defaultName = 'module:stop';
    protected static $defaultDescription = '暂停一个应用模块';

    protected function configure()
    {
        $this->addArgument('module', InputArgument::REQUIRED, '模块名');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('module');
        $modules = Module::getInstance()->getApplications();
        $moduleNames = array_column($modules, 'name');
        if (!in_array($moduleName, $moduleNames)) {
            throw new InvalidArgumentException('该模块不存在');
        }

        $moduleValue = Module::getInstance()->find($moduleName);
        $moduleValue['activity'] = false;
        $filename = module_path($moduleName) . DIRECTORY_SEPARATOR . 'module.json';
        file_put_contents($filename, json_encode($moduleValue, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info("$moduleName 应用模块已暂停");
        return self::SUCCESS;
    }
}