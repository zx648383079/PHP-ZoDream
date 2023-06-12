<?php
/*
 * @Author: zodream
 * @Date: 2020-06-13 13:48:44
 * @LastEditors: zodream
 * @LastEditTime: 2020-09-08 22:33:08
 */
namespace Module\Task;

use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Module\Task\Domain\Cron\FinishTask;
use Module\Task\Domain\Migrations\CreateTaskTables;
use Zodream\Route\Controller\Module as BaseModule;

/**
 * TODO 任务细分，分子任务进度，允许提前完成
 */
class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateTaskTables();
    }

    public function registerSchedule(Scheduler $scheduler): void {
        $scheduler->call(function () {
            new FinishTask();
        })->everyMinute();
    }
}