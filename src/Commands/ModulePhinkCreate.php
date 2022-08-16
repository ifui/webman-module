<?php

namespace Ifui\WebmanModule\Commands;

use Exception;
use Ifui\WebmanModule\Module;
use InvalidArgumentException;
use Phinx\Console\Command\Create;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModulePhinkCreate extends Create
{
    protected static $defaultName = 'module:phink-create';
    protected static $defaultDescription = 'Phink command';

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
        if ($input->getOption('module') == null) {
            $symfony = new SymfonyStyle($input, $output);
            $applications = Module::getInstance()->getApplications();
            $module = $symfony->choice('请选择操作模块', array_column($applications, 'name'));
            $input->setOption('module', $module);
        }
        return parent::execute($input, $output);
    }

    /**
     * Returns config file path
     *
     * @param InputInterface $input Input
     * @return string
     * @throws Exception
     */
    protected function locateConfigFile(InputInterface $input)
    {
        if ($input->getOption('configuration') == null) {
            $input->setOption('configuration', config_path()
                . DIRECTORY_SEPARATOR . 'plugin'
                . DIRECTORY_SEPARATOR . 'ifui'
                . DIRECTORY_SEPARATOR . 'webman-module'
                . DIRECTORY_SEPARATOR . 'phink.php');

            $input->setOption('path', module_path($input->getOption('module'))
                . DIRECTORY_SEPARATOR . 'database'
                . DIRECTORY_SEPARATOR . 'migrations'
            );
        }
        return parent::locateConfigFile($input);
    }
}