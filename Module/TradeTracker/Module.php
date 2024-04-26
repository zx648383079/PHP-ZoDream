<?php
declare(strict_types=1);
namespace Module\TradeTracker;

use Module\TradeTracker\Domain\Migrations\CreateTradeTrackerTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTradeTrackerTables();
    }
}