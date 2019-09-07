<?php
namespace Module\Game\Bank;

use Module\Game\Bank\Domain\Migrations\CreateBankTables;
use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ScheduleAble {

    public function getMigration() {
        return new CreateBankTables();
    }

    public function registerSchedule(Scheduler $scheduler) {
        $scheduler->call(function () {
            BankLogModel::balance();
        })->everyMinute();
    }
}