<?php
declare(strict_types=1);
namespace Module\Game\GameMaker;

use Module\Game\GameMaker\Domain\Migrations\CreateGameMakerTables;
use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateGameMakerTables();
    }

    public function registerSchedule(Scheduler $scheduler): void {
        $scheduler->call(function () {

        })->everyMinute();
    }
}