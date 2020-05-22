<?php
namespace Module\Task;

use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Module\Task\Domain\Cron\FinishTask;
use Module\Task\Domain\Migrations\CreateTaskTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateTaskTables();
    }

    public function registerSchedule(Scheduler $scheduler) {
        $scheduler->call(function () {
            new FinishTask();
        })->everyMinute();
    }
}