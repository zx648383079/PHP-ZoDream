<?php
namespace Module\Book;

use Module\Book\Domain\Migrations\CreateBookTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateBookTables();
    }
}