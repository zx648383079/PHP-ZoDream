<?php
namespace Module\Shop;

use Module\Schedule\Domain\ScheduleAble;
use Module\Schedule\Domain\Scheduler;
use Module\Shop\Domain\Cart\Cart;
use Module\Shop\Domain\Cron\ExpiredOrder;
use Module\Shop\Domain\Migrations\CreateShopTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ScheduleAble {

    public function boot() {
        app()->register('cart', Cart::class);
    }

    public function getMigration() {
        return new CreateShopTables();
    }

    public function registerSchedule(Scheduler $scheduler) {
        $scheduler->call(function () {
           new ExpiredOrder();
        })->everyMinute();
    }

    /**
     * @return Cart
     * @throws \Exception
     */
    public static function cart() {
        return app('cart');
    }
}