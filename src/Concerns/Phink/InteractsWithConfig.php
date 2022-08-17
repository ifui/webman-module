<?php

namespace Ifui\WebmanModule\Concerns\Phink;

use InvalidArgumentException;
use Phinx\Config\Config;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait InteractsWithConfig
{
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