<?php
declare(strict_types=1);
namespace Module\Navigation;

use Module\Navigation\Domain\Migrations\CreateNavigationTables;
use Zodream\Route\Controller\Module as BaseModule;

/**
 * 搜索引擎模块
 */
class Module extends BaseModule {

    public function getMigration() {
        return new CreateNavigationTables();
    }
}