<?php

namespace Ifui\WebmanModule\Generators;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Webman\Config;

class StubGenerator extends Generator
{
    /**
     * The replaces for stub names.
     *
     * @var array
     */
    protected $replaces;

    /**
     * Generator the stub file.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function generator()
    {
        $stubPaths = Config::get('plugin.ifui.webman-module.app.paths.stub', []);

        foreach ($stubPaths as $key => $config) {
            $configTo = $this->path . $config['to'];
            $filepath = $this->replaceStub($configTo);

            $this->generatorStub($config['from'], $filepath);
        }
    }

    /**
     * Replace the Plugin Name for the given stub.
     *
     * @param string $stub
     * @return array|string
     */
    public function replaceStub(string &$stub)
    {
        foreach ($this->replaces as $key => $value) {
            $stub = str_replace("{{ $key }}", $value ?? '', $stub);
            $stub = str_replace("{{$key}}", $value ?? '', $stub);
        }

        return $stub;
    }

    /**
     * Create a single stub file.
     *
     * @param $fromPath
     * @param $toPath
     * @return void
     * @throws FileNotFoundException
     */
    public function generatorStub($fromPath, $toPath)
    {
        $stubPath = $this->getStubPath($fromPath);

        if (!$this->filesystem->isDirectory($dir = dirname($toPath))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

        $this->filesystem->put($toPath, $this->getStubContent($stubPath));
        $this->command->info("Created {$toPath}");
    }

    /**
     * Return stub file path.
     *
     * @param $path
     * @return string
     */
    public function getStubPath($path)
    {
        return Config::get('plugin.ifui.webman-module.app.paths.stub_path') . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * Get the stub file for the generator.
     *
     * @param string $stubPath
     * @return array|string
     * @throws FileNotFoundException
     */
    public function getStubContent(string $stubPath)
    {
        $stub = $this->filesystem->get($stubPath);

        return $this->replaceStub($stub);
    }

    /**
     * Set replaces value.
     *
     * @param $replaces
     * @return StubGenerator
     */
    public function setReplaces($replaces)
    {
        $this->replaces = $replaces;

        return $this;
    }
}