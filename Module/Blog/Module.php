<?php
namespace Module\Blog;

use Module\Blog\Domain\Helpers\RouterHelper;
use Module\Blog\Domain\Migrations\CreateBlogTables;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateBlogTables();
    }

    public function openLinks(SiteMap $map) {
        RouterHelper::openLinks($map);
    }
}