<?php
namespace Module\Blog;

use Module\Blog\Domain\Migrations\CreateBlogTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateBlogTables();
    }
}