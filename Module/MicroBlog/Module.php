<?php
declare(strict_types=1);
namespace Module\MicroBlog;

use Module\MicroBlog\Domain\Migrations\CreateMicroBlogTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateMicroBlogTables();
    }
}