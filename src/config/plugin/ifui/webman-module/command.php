<?php

use Ifui\WebmanModule\Commands\ModuleCreateCommand;
use Ifui\WebmanModule\Commands\ModuleListCommand;
use Ifui\WebmanModule\Commands\ModuleMakeController;
use Ifui\WebmanModule\Commands\ModuleMakeMiddleware;
use Ifui\WebmanModule\Commands\ModuleMakeModel;
use Ifui\WebmanModule\Commands\ModuleMakeTest;
use Ifui\WebmanModule\Commands\ModulePhinkCreate;
use Ifui\WebmanModule\Commands\ModulePhinkMigrate;
use Ifui\WebmanModule\Commands\ModulePhinkRollback;
use Ifui\WebmanModule\Commands\ModulePhinkSeedCreate;
use Ifui\WebmanModule\Commands\ModulePhinkSeedRun;
use Ifui\WebmanModule\Commands\ModulePhinkStatus;

return [
    ModuleListCommand::class,
    ModuleCreateCommand::class,
    ModulePhinkCreate::class,
    ModulePhinkMigrate::class,
    ModulePhinkRollback::class,
    ModulePhinkStatus::class,
    ModulePhinkSeedCreate::class,
    ModulePhinkSeedRun::class,
    ModuleMakeController::class,
    ModuleMakeModel::class,
    ModuleMakeMiddleware::class,
    ModuleMakeTest::class,
];