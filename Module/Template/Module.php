<?php
namespace Module\Template;

use Module\Template\Domain\Migrations\CreateTemplateTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTemplateTables();
    }
}