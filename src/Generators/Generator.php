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
     * The Command instance.
     *
     * @var Command|null
     */
    protected $command;

    /**
     * The construct.
     *
     * @param string $name
     * @param Command|null $command
     */
    public function __construct(
        string     $name,
        Command    $command = null
    )
    {
        $this->name = $name;
        $this->path = Config::get('plugin.ifui.webman-module.app.paths.module') . DIRECTORY_SEPARATOR . $name;
        $this->command = $command;
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