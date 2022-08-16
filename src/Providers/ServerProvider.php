<?php

namespace Ifui\WebmanModule\Providers;

use Psr\Container\ContainerInterface;
use support\Container;
use Workerman\Worker;

abstract class ServerProvider
{
    /**
     * The application instance.
     *
     * @var ContainerInterface|null
     */
    protected ?ContainerInterface $container;

    /**
     * The Worker instance.
     *
     * @var Worker|null
     */
    protected ?Worker $worker;

    /**
     * Create a new service provider instance.
     *
     * @param Worker|null $worker
     * @return void
     */
    public function __construct(?Worker $worker)
    {
        $this->container = Container::instance();
        $this->worker = $worker;
    }

    /**
     * Call the method when container started.
     *
     * @return void
     */
    abstract public function boot(): void;
}