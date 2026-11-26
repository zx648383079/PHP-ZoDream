<?php
declare(strict_types=1);
namespace Module\Game\Miner;

use Module\Game\Miner\Domain\Migrations\CreateMinerTables;
use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateMinerTables();
    }

    public function registerSchedule(Scheduler $scheduler): void {
        $scheduler->call(function () {

        })->everyMinute();
    }
}