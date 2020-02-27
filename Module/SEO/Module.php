<?php
namespace Module\SEO;

use Zodream\Route\Controller\Module as BaseModule;
use Module\SEO\Domain\Migrations\CreateSEOTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateSEOTables();
    }

}