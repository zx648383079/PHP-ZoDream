<?php
namespace Module\Document;

use Module\Document\Domain\Migrations\CreateDocumentTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateDocumentTables();
    }
}