<?php
namespace Module\Game\GameMaker;

use Module\Game\GameMaker\Domain\Migrations\CreateGameMakerTables;
use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateGameMakerTables();
    }

    public function registerSchedule(Scheduler $scheduler) {
        $scheduler->call(function () {

        })->everyMinute();
    }
}