<?php
namespace Module\Note;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Note\Domain\Migrations\CreateNoteTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateNoteTables();
    }

}