<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Module;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModulePhinkNew extends Command
{
    protected static $defaultName = 'module:phink-new';
    protected static $defaultDescription = '为模块新建一个数据库迁移文件';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('filename', InputArgument::REQUIRED, '迁移文件名称');
        $this->addArgument('module', InputArgument::OPTIONAL, '模块名');
    }

    /**
     * 执行命令
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getArgument('filename');
        $module = $input->getArgument('module');
        if (empty($module)) {
            $moduleApps = Module::getInstance()->getApplications();
            $this->choice('请选择相应模块名', array_column($moduleApps, 'name'));
        }


        return self::SUCCESS;
    }
}