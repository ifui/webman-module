<?php

namespace Ifui\WebmanModule\Testing;

use Ifui\WebmanModule\Testing\Traits\MakeHttpRequest;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MakeHttpRequest;

    protected function setUp(): void
    {
        $this->createApplication();
    }

    /**
     * Create the webman application.
     *
     * @return void
     */
    abstract public function createApplication();
}