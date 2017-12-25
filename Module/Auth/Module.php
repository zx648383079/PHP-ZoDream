<?php
namespace Module\Auth;

use Module\Auth\Domain\Migrations\CreateAuthTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateAuthTables();
    }

    public function getControllerNamespace() {
        $prefix = parent::getControllerNamespace();
        if (APP_MODULE != 'Home') {
            return $prefix .'\\'. APP_MODULE;
        }
        return $prefix;
    }
}