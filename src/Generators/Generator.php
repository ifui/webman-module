<?php

namespace Ifui\WebmanModule\Generators;

use Ifui\WebmanModule\Commands\Command;
use Illuminate\Filesystem\Filesystem;
use Webman\Config;

abstract class Generator
{
    /**
     * The container name.
     *
     * @var string
     */
    protected $name;

    /**
     * The container path.
     *
     * @var string
     */
    protected $path;

    /**
     * The filesystem instance.
     *
     * @var Filesystem|null
     */
    protected $filesystem;

    /**
     * The Command instance.
     *
     * @var Command|null
     */
    protected $command;

    /**
     * The construct.
     *
     * @param string $name
     * @param Filesystem|null $filesystem
     * @param Command|null $command
     */
    public function __construct(
        string     $name,
        Filesystem $filesystem = null,
        Command    $command = null
    )
    {
        $this->name = $name;
        $this->path = Config::get('plugin.ifui.webman-module.app.paths.module') . DIRECTORY_SEPARATOR . $name;
        $this->filesystem = $filesystem;
        $this->command = $command;
    }

    /**
     * Set fileSystem instance.
     *
     * @param Filesystem $filesystem
     * @return Generator
     */
    public function setFileSystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * Set command instance.
     *
     * @param Command $command
     * @return Generator
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;
        return $this;
    }
}