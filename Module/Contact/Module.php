<?php
namespace Module\Contact;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Contact\Domain\Migrations\CreateContactTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateContactTables();
    }

}