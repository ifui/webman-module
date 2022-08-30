<?php

namespace Ifui\WebmanModule\Commands;


use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Generators\StubGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webman\Config;
use Webman\Exception\FileException;

class ModuleMakeController extends Command
{
    use InteractsWithModule {
        execute as traitExecute;
        configure as traitConfigure;
    }

    protected static $defaultName = 'module:make-controller';
    protected static $defaultDescription = '生成一个控制器文件';

    /**
     * Overwrite configure method.
     *
     * @return void
     */
    protected function configure()
    {
        $this->traitConfigure();
        $this->addArgument('filename', InputArgument::REQUIRED, '文件名');
    }

    /**
     * 执行命令
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->traitExecute($input, $output);
        $moduleName = $input->getOption('module');
        $filename = $input->getArgument('filename');
        $filepath = module_path($moduleName, $this->getGeneratorPath()) . '/' . $filename . '.php';
        $filepath = str_replace('\\', '/', $filepath);

        if (file_exists($filepath)) {
            throw new FileException($filepath . ' 文件已存在');
        }

        $namespace = $this->getNamespace();
        $className = $this->getClassName();

        $replaces = [
            'moduleName' => $moduleName,
            'namespace' => $namespace,
            'className' => $className
        ];

        // Generator stubs
        with(new StubGenerator($moduleName, $this))
            ->setReplaces($replaces)
            ->generatorStub('controller/base.stub', $filepath);

        return self::SUCCESS;
    }

    /**
     * 获取文件生成地址
     *
     * @return string
     */
    protected function getGeneratorPath()
    {
        return Config::get('plugin.ifui.webman-module.app.paths.generator.controller');
    }

    /**
     * 获取文件命名空间
     *
     * @return string
     */
    protected function getNamespace()
    {
        $filenameArr = $this->getFilenameArr();
        unset($filenameArr[count($filenameArr) - 1]);

        $namespaceArr = array_merge([
            Config::get('plugin.ifui.webman-module.app.namespace'),
            $this->input->getOption('module'),
            $this->getGeneratorPath()
        ], $filenameArr);

        $namespace = implode('\\', $namespaceArr);

        return str_replace('/', '\\', $namespace);
    }

    /**
     * 获取用户输入的文件名，并返回数组格式
     *
     * @return array
     */
    protected function getFilenameArr()
    {
        $filename = str_replace("\\", '/', $this->input->getArgument('filename'));
        return explode('/', $filename);
    }

    /**
     * 获取文件的类名
     *
     * @return string
     */
    protected function getClassName()
    {
        $filenameArr = $this->getFilenameArr();
        return (string)$filenameArr[count($filenameArr) - 1];
    }
}