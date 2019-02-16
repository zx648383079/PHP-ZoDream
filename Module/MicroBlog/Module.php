<?php
namespace Module\MicroBlog;

use Module\MicroBlog\Domain\Migrations\CreateMicroBlogTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateMicroBlogTables();
    }
}