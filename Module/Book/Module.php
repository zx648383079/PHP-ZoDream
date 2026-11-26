<?php
declare(strict_types=1);
namespace Module\Book;

use Module\Book\Domain\Migrations\CreateBookTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateBookTables();
    }
}