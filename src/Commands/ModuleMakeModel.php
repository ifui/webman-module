<?php

namespace Ifui\WebmanModule\Commands;

use Ifui\WebmanModule\Concerns\Command\InteractsWithModule;
use Ifui\WebmanModule\Generators\StubGenerator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use support\Db;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webman\Config;
use Webman\Console\Util;
use Webman\Exception\FileException;

class ModuleMakeModel extends Command
{
    use InteractsWithModule {
        execute as traitExecute;
        configure as traitConfigure;
    }

    protected static $defaultName = 'module:make-model';
    protected static $defaultDescription = '生成一个 model 文件';
    protected $pk = 'id';

    /**
     * Overwrite configure method.
     *
     * @return void
     */
    protected function configure()
    {
        $this->traitConfigure();
        $this->addArgument('filename', InputArgument::REQUIRED, 'model 名称');
    }

    /**
     * 执行命令
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FileNotFoundException
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
        $properties = $this->getProperties();
        $table = $this->getTableName();
        $pk = $this->pk;

        $replaces = [
            'moduleName' => $moduleName,
            'namespace' => $namespace,
            'className' => $className,
            'properties' => $properties,
            'table' => $table,
            'pk' => $pk
        ];

        $filesystem = new Filesystem();

        // Generator stubs
        $stubPath = (!Config::get('database') && Config::get('thinkorm')) ? 'model/tpModel.stub' : 'model/model.stub';
        with(new StubGenerator($moduleName, $filesystem, $this))
            ->setReplaces($replaces)
            ->generatorStub($stubPath, $filepath);

        return self::SUCCESS;
    }

    /**
     * 获取文件生成地址
     *
     * @return string
     */
    protected function getGeneratorPath()
    {
        return Config::get('plugin.ifui.webman-module.app.paths.generator.model');
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

    /**
     * 获取数据库字段映射
     *
     * @return string
     */
    protected function getProperties()
    {
        $table = $this->getTableName();
        $database = Config::get('database.connections.mysql.database');
        $properties = '';
        foreach (Db::select("select COLUMN_NAME,DATA_TYPE,COLUMN_KEY,COLUMN_COMMENT from INFORMATION_SCHEMA.COLUMNS where table_name = '$table' and table_schema = '$database'") as $item) {
            if ($item->COLUMN_KEY === 'PRI') {
                $this->pk = $item->COLUMN_NAME;
                $item->COLUMN_COMMENT .= "(主键)";
            }
            $type = $this->getType($item->DATA_TYPE);
            $properties .= " * @property $type \${$item->COLUMN_NAME} {$item->COLUMN_COMMENT}\n";
        }

        return $properties;
    }

    /**
     * 获取数据表名
     *
     * @return string
     */
    protected function getTableName()
    {
        $prefix = Config::get('database.connections.mysql.prefix');
        $table = Util::classToName($this->input->getArgument('filename'));
        if (Db::select("show tables like '{$prefix}{$table}s'")) {
            $table = "{$prefix}{$table}s";
        } else if (Db::select("show tables like '{$prefix}{$table}'")) {
            $table = "{$prefix}{$table}";
        }
        return $table;
    }

    /**
     * 获取数据库表键值类型
     *
     * @param string $type
     * @return string
     */
    protected function getType(string $type)
    {
        if (str_contains($type, 'int')) {
            return 'integer';
        }
        switch ($type) {
            case 'varchar':
            case 'string':
            case 'text':
            case 'date':
            case 'time':
            case 'guid':
            case 'datetimetz':
            case 'datetime':
            case 'decimal':
            case 'enum':
                return 'string';
            case 'boolean':
                return 'integer';
            case 'float':
                return 'float';
            default:
                return 'mixed';
        }
    }
}