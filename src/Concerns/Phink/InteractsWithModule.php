<?php

namespace Ifui\WebmanModule\Concerns\Phink;

use Ifui\WebmanModule\Module;
use InvalidArgumentException;
use Phinx\Config\Config;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

trait InteractsWithModule
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption('--module', '-m', InputArgument::OPTIONAL, '模块名');
    }

    /**
     * Create the new migration.
     *
     * @param InputInterface $input Input
     * @param OutputInterface $output Output
     * @return int 0 on success
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $input->getOption('module');
        $moduleApplications = Module::getInstance()->getApplications();

        if ($input->getOption('module') == null) {
            $symfony = new SymfonyStyle($input, $output);
            $applications = Module::getInstance()->getApplications();
            $module = $symfony->choice('请选择操作模块', array_column($applications, 'name'));
            $input->setOption('module', $module);
        } elseif (!in_array($module, array_column($moduleApplications, 'name'))) {
            throw new InvalidArgumentException('该模块不存在，请检查 module 是否正确');
        }

        return parent::execute($input, $output);
    }

    /**
     * Overwrite loadConfig Method.
     *
     * @param InputInterface $input Input
     * @param OutputInterface $output Output
     * @return void
     * @throws InvalidArgumentException
     */
    protected function loadConfig(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('configuration') == null) {
            // Get module name
            $module = $input->getOption('module');

            $configFilePath = config_path()
                . DIRECTORY_SEPARATOR . 'plugin'
                . DIRECTORY_SEPARATOR . 'ifui'
                . DIRECTORY_SEPARATOR . 'webman-module'
                . DIRECTORY_SEPARATOR . 'phink.php';
            $input->setOption('configuration', $configFilePath);
            $phinkConfig = require $configFilePath;
            // Reassembly paths
            $phinkConfig['paths']['migrations'] = module_path($module) . '/' . $phinkConfig['paths']['migrations'];
            $phinkConfig['paths']['seeds'] = module_path($module) . '/' . $phinkConfig['paths']['seeds'];

            $config = new Config($phinkConfig);
            $this->setConfig($config);
        } else {
            parent::loadConfig($input, $output);
        }
    }
}