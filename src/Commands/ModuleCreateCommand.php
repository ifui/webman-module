<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Generators\FolderGenerator;
use Ifui\WebmanModule\Generators\StubGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webman\Config;

class ModuleCreateCommand extends Command
{
    protected static $defaultName = 'module:create';
    protected static $defaultDescription = '新建一个模块';

    /**
     * 执行命令
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->input = $input;

        $name = $this->ask('请输入应用名（必填）');
        if (empty($name)) {
            $this->error('请输入应用名');
            return self::FAILURE;
        }
        $name = trim($name);

        $studlyName = array_map(function ($word) {
            return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, null, 'UTF-8');
        }, explode(' ', str_replace(['-', '_'], ' ', $name)));
        $studlyName = implode($studlyName);

        $author = $this->ask('请输入作者名（必填）');
        $email = $this->ask('请输入邮箱地址（选填）');
        $description = $this->ask('请输入应用简介（选填）');
        $homepage = $this->ask('请输入主页地址（选填）');

        // Define the market namespace
        $namespace = Config::get('plugin.ifui.webman-module.app.namespace', 'plugin') . '\\' . $name;
        $namespaceComposer = Config::get('plugin.ifui.webman-module.app.namespace', 'plugin') . '\\\\' . $name . '\\\\';

        $replaces = [
            'name' => $name,
            'studlyName' => $studlyName,
            'lowerName' => strtolower($name),
            'author' => $author,
            'email' => $email,
            'description' => $description,
            'homepage' => $homepage,
            'namespace' => $namespace,
            'namespaceComposer' => $namespaceComposer
        ];

        // Generator folders
        with(new FolderGenerator($name, $this))->generator();

        // Generator stubs
        with(new StubGenerator($name, $this))
            ->setReplaces($replaces)
            ->generator();

        $this->info("应用 [$name] 已创建成功.");

        return self::SUCCESS;
    }
}