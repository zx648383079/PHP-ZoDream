<?php
namespace Module\Shop;

use Module\Shop\Domain\Migrations\CreateShopTables;
use Module\Shop\Domain\ShoppingCart;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;

class Module extends BaseModule {

    public function boot() {
        app()->register(ShoppingCart::class);
    }

    public function getMigration() {
        return new CreateShopTables();
    }

    /**
     * @return ShoppingCart
     * @throws \Exception
     */
    public static function cart() {
        return app(ShoppingCart::class);
    }
}