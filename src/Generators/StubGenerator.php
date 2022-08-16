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
    protected array $replaces;

    /**
     * Generator the laravel-plugin.json file.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function generator(): void
    {
        $stubPaths = Config::get('plugin.ifui.webman-module.app.paths.stub', []);

        foreach ($stubPaths as $key => $config) {
            $configTo = $this->path . $config['to'];
            $filepath = $this->replaceStub($configTo);

            $stubPath = __DIR__ . '/../../stubs/' . $config['from'];

            if (!$this->filesystem->isDirectory($dir = dirname($filepath))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($filepath, $this->getStubContent($stubPath));
            $this->command->info("Created {$filepath}");
        }
    }

    /**
     * Replace the Plugin Name for the given stub.
     *
     * @param string $stub
     * @return array|string
     */
    public function replaceStub(string &$stub): array|string
    {
        foreach ($this->replaces as $key => $value) {
            $stub = str_replace("{{ $key }}", $value ?? '', $stub);
            $stub = str_replace("{{$key}}", $value ?? '', $stub);
        }

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @param string $stubPath
     * @return array|string
     * @throws FileNotFoundException
     */
    public function getStubContent(string $stubPath): array|string
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
    public function setReplaces($replaces): static
    {
        $this->replaces = $replaces;

        return $this;
    }
}