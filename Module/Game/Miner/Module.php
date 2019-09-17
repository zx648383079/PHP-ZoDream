<?php
namespace Module\Game\Miner;

use Module\Game\Miner\Domain\Migrations\CreateMinerTables;
use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateMinerTables();
    }

    public function registerSchedule(Scheduler $scheduler) {
        $scheduler->call(function () {

        })->everyMinute();
    }
}