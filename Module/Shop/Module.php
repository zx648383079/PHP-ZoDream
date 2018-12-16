<?php
namespace Module\Shop;

use Module\Shop\Domain\Cart\Cart;
use Module\Shop\Domain\Migrations\CreateShopTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function boot() {
        app()->register('cart', Cart::class);
    }

    public function getMigration() {
        return new CreateShopTables();
    }

    /**
     * @return Cart
     * @throws \Exception
     */
    public static function cart() {
        return app('cart');
    }
}